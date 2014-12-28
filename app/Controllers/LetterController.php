<?php   namespace Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use SaleBoss\Repositories\Eloquent\UploadRepository;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Repositories\GroupRepositoryInterface;
use SaleBoss\Repositories\LetterLogRepositoryInterface;
use SaleBoss\Repositories\LetterRepositoryInterface;
use SaleBoss\Repositories\UploadRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Folder\FolderListCommand;
use SaleBoss\Services\Letter\LetterActionsCommand;
use SaleBoss\Services\Letter\LetterStoreCommand;
use SaleBoss\Services\Upload\Facades\Upload;
use SaleBoss\Services\Validator\LetterValidator;

class LetterController extends BaseController {

    protected $auth;

    protected $userRepo;

    /**
     * @var LetterRepositoryInterface
     */
    private $letterRepo;

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepo;

    /**
     * @var LetterValidator
     */
    private $letterValidator;

    /**
     * @var FolderRepositoryInterface
     */
    private $folderRepo;

    /**
     * @var LetterLogRepositoryInterface
     */
    private $letterLogRepo;

    /**
     * @var UploadRepositoryInterface
     */
    private $uploadRepo;

    /**
     * @param AuthenticatorInterface       $authenticator
     * @param UserRepositoryInterface      $userRepository
     * @param LetterRepositoryInterface    $letterRepository
     * @param GroupRepositoryInterface     $groupRepository
     * @param FolderRepositoryInterface    $folderRepository
     * @param LetterLogRepositoryInterface $letterLogRepository
     * @param UploadRepositoryInterface    $uploadRepository
     * @param LetterValidator              $letterValidator
     */
    function __construct(
        AuthenticatorInterface $authenticator,
        UserRepositoryInterface $userRepository,
        LetterRepositoryInterface $letterRepository,
        GroupRepositoryInterface $groupRepository,
        FolderRepositoryInterface $folderRepository,
        LetterLogRepositoryInterface $letterLogRepository,
        UploadRepositoryInterface $uploadRepository,
        LetterValidator $letterValidator
    )
    {
        $this->auth = $authenticator;
        $this->userRepo = $userRepository;
        $this->letterRepo = $letterRepository;
        $this->groupRepo = $groupRepository;
        $this->letterValidator = $letterValidator;
        $this->folderRepo = $folderRepository;
        $this->letterLogRepo = $letterLogRepository;
        $this->uploadRepo = $uploadRepository;
    }

	/**
     * Show index list of all folders
     * @return mixed
     */
    public function index()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $letters = $this->letterRepo->getMyInbox($userId, $userGroupId);

        return $this->view('admin.pages.letter.index', compact('letters','userGroupId'));
    }

    public function indexSearch()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $inputs = Input::except('_token', 'page', '_url') ?: null;

        $letters = $this->letterRepo->getSearch($inputs);

        return $this->view('admin.pages.letter.search', compact('letters', 'userGroupId', 'userId'));
    }

    public function indexSent()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $letters = $this->letterRepo->getMyOutbox($userId, $userGroupId);

        return $this->view('admin.pages.letter.out_index', compact('letters','userGroupId'));
    }

    public function create()
    {
        $userGroupId = $this->groupRepo->getUserGroups($this->auth->user())->first()->id;

        $userId = $this->auth->user()->getId();

        $folders = $this->execute(FolderListCommand::class, compact('userId', 'userGroupId'));

        $groups = $this->groupRepo->getAll();

        return $this->view('admin.pages.letter.create', compact('groups', 'folders'));
    }

    public function store()
    {
        $valid = $this->letterValidator->isValid(Input::get('item'));
        if (!$valid) {
            return $this->redirectBack()
                        ->withErrors($this->letterValidator->getMessages())
                        ->withInput();
        }

        $input = [
            'user'          => $this->auth->user()->getId(),
            'subject'       => Input::get('item')['subject'],
            'message'       => Input::get('item')['message'],
            'folder'        => Input::get('item')['folder'],
            'path'          => [
                'start'         => $this->groupRepo->getUserGroups($this->auth->user())->first()->id,
                'destination'   => array_map('intval', Input::get('item')['destination'])
            ],
            'file'      =>  is_null(Input::file('file')) ? null : Input::file('file')
        ];

        if($this->execute(LetterStoreCommand::class, $input))
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        else
            return $this->redirectBack()->withErrors(Lang::get('messages.operation_error'));
    }

    public function show($id, $toId)
    {
        $letter = $this->letterRepo->findByIdAndDestination($id, $toId);

        $logs = $this->letterLogRepo->getList($letter->letter_id, $letter->id);

        $files = $this->uploadRepo->getList($letter->letter_id, 'SaleBoss\Models\Letter');

        return $this->view('admin.pages.letter.show', compact('letter', 'logs', 'files'));
    }

    public function action()
    {
        $input = [
            'user' => $this->auth->user(),
            'letterId' => Input::get('letterId'),
            'actionType' => Input::get('actionType'),
            'destinationId' => Input::get('destinationId'),
            'logMessage'    => Input::get('logMessage')
        ];

        $action = $this->execute(LetterActionsCommand::class, $input);

        if($action)
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        else
            return $this->redirectBack()->with('success_message',Lang::get("messages.permission_denied"));
    }

}