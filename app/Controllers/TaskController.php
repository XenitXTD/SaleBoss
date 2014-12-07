<?php   namespace Controllers;

use Miladr\Jalali;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use SaleBoss\Repositories\TaskRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Tasks\TaskActionsCommand;
use SaleBoss\Services\Tasks\TaskEditCommand;
use SaleBoss\Services\Tasks\TaskMessagesUpdateCommand;
use SaleBoss\Services\Tasks\TaskShowCommand;
use SaleBoss\Services\Tasks\TasksIndexCommand;
use SaleBoss\Services\Tasks\TaskStoreCommand;
use SaleBoss\Services\Validator\TaskValidator;

class TaskController extends BaseController {

    protected $userRepo;
    protected  $auth;
    protected $taskRepo;
    protected $taskValidator;

    /**
     * @param AuthenticatorInterface  $auth
     * @param UserRepositoryInterface $user
     * @param TaskRepositoryInterface $task
     * @param TaskValidator           $taskValidator
     */
    function __construct(
        AuthenticatorInterface $auth,
        UserRepositoryInterface $user,
        TaskRepositoryInterface $task,
        TaskValidator $taskValidator
    )
    {
        $this->auth = $auth;
        $this->userRepo = $user;
        $this->taskRepo = $task;
        $this->taskValidator = $taskValidator;
    }

	/**
     * Show index list of all my tasks
     * @return mixed
     */
    public function index()
    {
        $user = $this->auth->user();
        $tasks = $this->execute(TasksIndexCommand::class, compact('user'));
        return $this->view('admin.pages.tasks.index', compact('tasks'));
    }

    public function action()
    {
        $input = [
            'user' => $this->auth->user(),
            'taskId' => Input::get('taskId'),
            'actionType' => Input::get('actionType'),
            'for_id' => Input::get('for_id')
        ];

        $action = $this->execute(TaskActionsCommand::class, $input);

        if($action)
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        else
            return $this->redirectBack()->with('success_message',Lang::get("messages.permission_denied"));
    }

	/**
     * Show task by id
     *
     * @param Task ID $id
     * @return mixed
     */
    public function show($id)
    {
        $input = ['user'=> $this->auth->user(), 'taskId' => $id];
        $user = $this->auth->user();
        $task = $this->execute(TaskShowCommand::class, $input);
        return $this->view('admin.pages.tasks.show', compact('task', 'user'));
    }

    public function edit($id)
    {
        if(Input::get('todo_at'))
        {
            $todo = Input::get('todo_at');
            $todoEx = explode('-', $todo);
            $todoG = Jalali\jDateTime::toGregorian($todoEx[0], $todoEx[1], $todoEx[2]);
            $todo = $todoG[0] . '-' . $todoG[1] . '-' . $todoG[2] . ' 00:00:00 00:00';

            $input = [
                'user'      =>   $this->auth->user(),
                'taskId'    =>   $id,
                'data'      =>  [
                    'for_id'  => Input::get('for_id'),
                    'priority'  => Input::get('priority'),
                    'status'    => Input::get('status'),
                    'category'  => Input::get('category'),
                    'description'   => Input::get('description'),
                    'todo_at'   =>  $todo
                ]
            ];

            $this->execute(TaskEditCommand::class, $input);
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        } else {
            $input = ['user' => $this->auth->user(), 'taskId' => $id];
            $task = $this->execute(TaskEditCommand::class, $input);
            return $this->view('admin.pages.tasks.edit', compact('task'));
        }
    }

	/**
     * Create base page for creating task
     *
     * @return mixed
     */
    public function create()
    {
        return $this->view('admin.pages.tasks.create');
    }

	/**
     * Store creating task method on eloquent
     *
     * @return mixed
     */
    public function store()
    {
        $valid = $this->taskValidator->isValid(Input::get('item'));
        if (!$valid) {
            return $this->redirectBack()
                        ->withErrors($this->taskValidator->getMessages())
                        ->withInput();
        }

            $info = Input::get('item');
            $todoEx = explode('-', $info['todo_at']);
            $todoG = Jalali\jDateTime::toGregorian($todoEx[0], $todoEx[1], $todoEx[2]);
            $todo = Jalali\jDate::forge($todoG[0] . '-' . $todoG[1] . '-' . $todoG[2])->time();

            $input = [
                'user'      =>   $this->auth->user(),
                'data'      =>  [
                    'for_id'  => $info['for_id'],
                    'priority'  => $info['priority'],
                    'status'    => $info['status'],
                    'category'  => $info['category'],
                    'description'   => $info['description'],
                    'todo_at'   =>  $todo,
                    'file'      =>  is_null(Input::file('file')) ? null : Input::file('file')
                ]
            ];

            $this->execute(TaskStoreCommand::class, $input);
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
    }

    public function messageUpdate()
    {
        $input = [
            'user' => $this->auth->user(),
            'taskId' => Input::get('taskId'),
            'for_id' => Input::get('for_id'),
            'message' => Input::get('message'),
            'notification' => true
        ];

        if($this->execute(TaskMessagesUpdateCommand::class, $input));

        return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
    }


}