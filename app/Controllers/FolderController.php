<?php   namespace Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Repositories\GroupRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Folder\FolderCreateCommand;
use SaleBoss\Services\Folder\FolderItemCreateCommand;
use SaleBoss\Services\Folder\FolderItemsListCommand;
use SaleBoss\Services\Folder\FolderListCommand;
use SaleBoss\Services\Letter\LetterListCommand;
use SaleBoss\Services\Validator\FolderItemValidator;
use SaleBoss\Services\Validator\FolderValidator;

class FolderController extends BaseController {

    protected $auth;

    protected $userRepo;

    protected $groupRepo;

    protected $folderRepo;

    /**
     * @var FolderValidator
     */
    private $folderValidator;

    /**
     * @var FolderItemValidator
     */
    private $folderItemValidator;

    /**
     * @param AuthenticatorInterface    $authenticator
     * @param UserRepositoryInterface   $userRepository
     * @param GroupRepositoryInterface  $groupRepository
     * @param FolderRepositoryInterface $folderRepository
     * @param FolderValidator           $folderValidator
     * @param FolderItemValidator       $folderItemValidator
     */
    function __construct(
        AuthenticatorInterface $authenticator,
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        FolderRepositoryInterface $folderRepository,
        FolderValidator $folderValidator,
        FolderItemValidator $folderItemValidator
    )
    {
        $this->auth = $authenticator;
        $this->userRepo = $userRepository;
        $this->groupRepo = $groupRepository;
        $this->folderRepo = $folderRepository;
        $this->folderValidator = $folderValidator;
        $this->folderItemValidator = $folderItemValidator;
    }

	/**
     * Show index list of all folders
     * @return mixed
     */
    public function index()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $userFolders = $this->folderRepo->getList('SaleBoss\Models\User', $userId);

        $groupFolders = $this->folderRepo->getList('SaleBoss\Models\Group', $userGroupId);

        $folders = $this->execute(FolderListCommand::class, compact('userId', 'userGroupId'));

        return $this->view('admin.pages.folder.index', compact('folders', 'userGroupId', 'userId', 'userFolders', 'groupFolders'));
    }

    public function lettersList($folderId)
    {
        try {
            $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

            $folder = $this->folderRepo->getById($folderId);

            $userId = $this->auth->user()->getId();

            $letters = $this->execute(LetterListCommand::class, compact('folderId', 'userId', 'userGroupId'));

            return $this->view('admin.pages.folder.letters', compact('letters', 'folder'));

        } catch(\Exception $e) {
            return Redirect::route('FolderIndex')->with('error_message','شما اجازه مشاهده این بخش را ندارید.');;
        }
    }

    public function store()
    {
        $input = Input::get('item');
        if (!$valid = $this->folderValidator->isValid($input)) {
            return $this->redirectBack()->withInput()->withErrors($this->folderValidator->getMessages());
        }

        $input = [
            'parent_id' => Input::get('item')['parent_id'],
            'name' => Input::get('item')['name'],
            'creator_id' => $this->auth->user()->getId(),
            'for_type' => Input::get('item')['for_type'],
            'for_id' => Input::get('item')['for_id']
        ];

        if($this->execute(FolderCreateCommand::class, $input))
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        else
            return $this->redirectBack()->withErrors(Lang::get('messages.operation_error'));
    }

    public function itemCreate()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $folders = $this->execute(FolderListCommand::class, compact('userId', 'userGroupId'));

        return $this->view('admin.pages.folder.itemCreate', compact('folders', 'userGroupId', 'userId'));
    }

    public function itemStore()
    {

        $input = Input::get('item');
        if (!$valid = $this->folderItemValidator->isValid($input)) {
            return $this->redirectBack()->withInput()->withErrors($this->folderItemValidator->getMessages());
        }

        $input = [
            'name' => Input::get('item')['name'],
            'creator_id' => $this->auth->user()->getId(),
            'for_id' => Input::get('item')['for_id'],
            'description' => Input::get('item')['description'],
            'file'      =>  is_null(Input::file('file')) ? null : Input::file('file')
        ];

        if($this->execute(FolderItemCreateCommand::class, $input))
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        else
            return $this->redirectBack()->withErrors(Lang::get('messages.operation_error'));
    }

    public function itemShow($id)
    {
        try {
            $item = $this->folderRepo->getItemById($id);
            return $this->view('admin.pages.folder.itemShow', compact('item'));
        } catch(\Exception $e) {
            return Redirect::route('FolderIndex')->with('error_message','امکان نمایش این آیتم وجود ندارد.');;
        }
    }

    public function itemsList($folderId)
    {
        $userId = $this->auth->user()->getId();

        $items = $this->execute(FolderItemsListCommand::class, compact('userId', 'folderId'));

        $items = $items->filter(function($item) use($folderId)
        {
            if(in_array($folderId, array_values(json_decode($item->for_id))))
            {
                return $item;
            }

        });

        return $this->view('admin.pages.folder.items', compact('items', 'folderId', 'userId'));
    }

    public function search()
    {
        $userId = $this->auth->user()->getId();

        $inputs = Input::except('_token', 'page', '_url') ?: null;

        $items = $this->folderRepo->getSearch($inputs, $userId);

        if(!is_null(Input::get('for_id')))
            $items = $items->filter(function($item)
            {
                if(in_array(Input::get('for_id'), array_values(json_decode($item->for_id))))
                {
                    return $item;
                }

            });

        return $this->view('admin.pages.folder.search', compact('items', 'userId'));
    }

    public function edit($id)
    {
        try {
                $folder = $this->folderRepo->getById($id);

                if($folder->creator_id == $this->auth->user()->getId())
                {
                    if($folder->for_type == 'SaleBoss\Models\User')
                    {
                        $folders = $this->folderRepo->getList('SaleBoss\Models\User', $this->groupRepo->getUserGroups($this->auth->user())->first()->id);
                    } else
                        {
                            $folders = $this->folderRepo->getList('SaleBoss\Models\Group', $this->groupRepo->getUserGroups($this->auth->user())->first()->id);
                        }

                    return $this->view('admin.pages.folder.edit', compact('folder', 'folders', 'model'));
                } else
                    return Redirect::route('FolderIndex')->with('error_message','امکان نمایش این آیتم وجود ندارد.');
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function update($id)
    {
        $input = Input::get('item');
        if (!$valid = $this->folderValidator->isValid($input)) {
            return $this->redirectBack()->withInput()->withErrors($this->folderValidator->getMessages());
        }

        try {
            $this->folderRepo->update($id, $input);
            return $this->redirectBack()->with('success_message', Lang::get('messages.operation_success'));
        } catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function delete($id)
    {
        try {
            $folder = $this->folderRepo->getById($id);

            if($folder->creator_id == $this->auth->user()->getId())
            {
                $this->folderRepo->delete($id);
                return $this->redirectTo('folder')->with('success_message', Lang::get('messages.operation_success'));
            } else
                return Redirect::route('FolderIndex')->with('error_message','امکان نمایش این آیتم وجود ندارد.');
        } catch (NotFoundException $e){
            App::abort(404);
        }
    }

}