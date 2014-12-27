<?php   namespace Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Paginator;
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

class FolderController extends BaseController {

    protected $auth;

    protected $userRepo;

    protected $groupRepo;

    protected $folderRepo;

    function __construct(
        AuthenticatorInterface $authenticator,
        UserRepositoryInterface $userRepository,
        GroupRepositoryInterface $groupRepository,
        FolderRepositoryInterface $folderRepository
    )
    {
        $this->auth = $authenticator;
        $this->userRepo = $userRepository;
        $this->groupRepo = $groupRepository;
        $this->folderRepo = $folderRepository;
    }

	/**
     * Show index list of all folders
     * @return mixed
     */
    public function index()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $folders = $this->execute(FolderListCommand::class, compact('userId', 'userGroupId'));

        return $this->view('admin.pages.folder.index', compact('folders', 'userGroupId', 'userId'));
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
            return $this->view('admin.pages.folder.itemShow', compact('item', 'userId'));
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

        $inputs = Input::except('_token', 'page') ?: null;

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

}