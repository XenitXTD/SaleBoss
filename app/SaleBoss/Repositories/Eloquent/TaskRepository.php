<?php namespace SaleBoss\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use SaleBoss\Models\Task;
use SaleBoss\Models\User;
use SaleBoss\Repositories\TaskRepositoryInterface;
use SaleBoss\Services\Notification\Facades\Notification;
use SaleBoss\Services\Upload\Facades\Upload;

class TaskRepository extends AbstractRepository implements TaskRepositoryInterface {

    protected $model;

    protected $taskMsgRepo;

    protected $notificationService;

    function __construct(
        Task $task,
        TaskMessagesRepository $taskMsg,
        Notification $notification
    )
    {
        $this->model = $task;
        $this->taskMsgRepo = $taskMsg;
        $this->notificationService = $notification;
    }

	/**
     * Get task list bu userId
     * This method is for tasks that user recieved
     *
     * @param User $user
     * @return \Illuminate\Pagination\Paginator
     */
    public function getTaskListForMe(User $user)
    {
        $query = $this->model->newInstance();

        $tasks = $query->with('forWhom')->where('for_id', '=', $user->getId())->where('status', '!=', 2)->orderBy('created_at', 'DSC')->paginate(50);

        return $tasks;
    }

	/**
     * Get task list by userId
     * This method is for tasks that user sent
     *
     * @param User $user
     * @return \Illuminate\Pagination\Paginator
     */
    public function getTaskListByMe(User $user)
    {
        $query = $this->model->newInstance();

        $tasks = $query->with('creator')->where('creator_id', '=', $user->getId())->orderBy('created_at', 'DSC')->paginate(50);

        return $tasks;
    }

	/**
     * Show task details
     *
     * @param User $user
     * @param      $taskId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function showTask(User $user, $taskId)
    {
        $query = $this->model->newInstance();

        $task = $query->with('forWhom', 'cat')->with(['message' => function($query) use($taskId, $user){
                $this->taskMsgRepo->setAsRead($user, $taskId);
                $query->where('task_id', $taskId)->orderBy('created_at', 'ASC');
            }])->where('id', $taskId)->first();

        Notification::setReadTasksNotifications($taskId);

        return $task;
    }

    /**
     * Create task
     * Send Notification to user
     *
     * @param User  $user
     * @param array $data
     * @return bool
     */
    public function createTask(User $user, array $data)
    {
        $model = $this->model->newInstance();

        $model->creator_id = $user->getId();
        $model->for_id = $data['for_id'];
        $model->priority = $data['priority'];
        $model->status = $data['status'];
        $model->description = $data['description'];
        $model->category = $data['category'];
        $model->todo_at = $data['todo_at'];

        if($model->save()) {

            Upload::doUpload($data['file'],
                             [
                                 'for_id'   => $model->id,
                                 'for_type' => Config::get('opilo_configs.notifications_types.Task'),
                                 'path'     =>  'files/tasks'
                             ]);

            $sendInformation = [
                'from_id'     => $model->id, // ID user that send the notification
                'to_id'       => $data['for_id'], // ID user that receive the notification
                'type' => [
                    'from_type' => Config::get('opilo_configs.notifications_types.Task'),
                    'to_type' => Config::get('opilo_configs.notifications_types.User')
                ],
                'category' => Config::get('opilo_configs.notifications_categories.Tasks'), // category notification ID
                'url'         => URL::to('task/'.$model->id), // Url of your notification
                'extra'       => Lang::get("tasks.notifications_create")
            ];

            Notification::sendNotification($sendInformation);

            return true;
        }
            else
                return false;
    }

	/**
     * Edit Task By Creator
     *
     * @param User  $user
     * @param       $taskId
     * @param array $data
     */
    public function editTask(User $user, $taskId, array $data)
    {
        $query = $this->model->newInstance();

        $q = $query->where('id', $taskId)->update(
            [
                'priority' => $data['priority'],
                'status' => $data['status'],
                'category' => $data['category'],
                'description' => $data['description'],
                'todo_at' => $data['todo_at']
            ]
        );

        if($q){
            $sendInformation = [
                'from_id'     => $taskId, // ID user that send the notification
                'to_id'       => $data['for_id'], // ID user that receive the notification
                'type' => [
                    'from_type' => Config::get('opilo_configs.notifications_types.Task'),
                    'to_type' => Config::get('opilo_configs.notifications_types.User')
                ],
                'category' => Config::get('opilo_configs.notifications_categories.Tasks'), // category notification ID
                'url'         => URL::to('task/'.$taskId), // Url of your notification
                'extra'       => Lang::get("tasks.notifications_edit")
            ];

            Notification::sendNotification($sendInformation);

            $this->taskMsgRepo->sendMessageOnTask($user, $taskId, Lang::get("messages.task_edited"), false, null);

            $task = $query->where('id', $taskId)->first();

            return $task;
        }

        return false;
    }

	/**
     * Set task status as To Be Done
     * Only creator can do this 
     *
     * @param User $user
     * @param      $taskId
     * @return bool
     */
    public function setAsToBeDone(User $user, $taskId)
    {
        $query = $this->model->newInstance();

        $task = $query->where('id', $taskId)->first();

        if($task->creator_id == $user->getId())
        {
            $query->where('id', $taskId)->update(['status'=>0]);

            $sendInformation = [
                'from_id'     => $taskId, // ID user that send the notification
                'to_id'       => $task->for_id, // ID user that receive the notification
                'type' => [
                    'from_type' => Config::get('opilo_configs.notifications_types.Task'),
                    'to_type' => Config::get('opilo_configs.notifications_types.User')
                ],
                'category' => Config::get('opilo_configs.notifications_categories.Tasks'), // category notification ID
                'url'         => URL::to('task/'.$taskId), // Url of your notification
                'extra'       => Lang::get("tasks.notifications_setAsToBeDone")
            ];

            Notification::sendNotification($sendInformation);
            return true;
        } else
            return false;
    }

	/**
     * Set task status as done
     * Both of creator and reciever can do this
     *
     * @param User $user
     * @param      $taskId
     * @return bool
     */
    public function setAsDone(User $user, $taskId, $for_id)
    {

        $query = $this->model->newInstance();

        $task = $query->where('id', $taskId)->first();

        if($task->creator_id == $user->getId() or $task->for_id == $user->getId())
        {
            $query->where('id', $taskId)->update(['status'=>1]);
            $sendInformation = [
                'from_id'     => $taskId, // ID user that send the notification
                'to_id'       => $for_id, // ID user that receive the notification
                'type' => [
                    'from_type' => Config::get('opilo_configs.notifications_types.Task'),
                    'to_type' => Config::get('opilo_configs.notifications_types.User')
                ],
                'category' => Config::get('opilo_configs.notifications_categories.Tasks'), // category notification ID
                'url'         => URL::to('task/'.$taskId), // Url of your notification
                'extra'       => Lang::get("tasks.notifications_setAsDone")
            ];

            Notification::sendNotification($sendInformation);
            return true;
        } else
            return false;

    }

	/**
     * Set task status as close
     * Only task creator can do this
     *
     * @param User $user
     * @param      $taskId
     * @return bool
     */
    public function setAsClose(User $user, $taskId)
    {
        $query = $this->model->newInstance();

        $task = $query->where('id', $taskId)->first();

        if($task->creator_id == $user->getId())
        {
            $query->where('id', $taskId)->update(['status'=>2]);
            $sendInformation = [
                'from_id'     => $taskId, // ID user that send the notification
                'to_id'       => $task->for_id, // ID user that receive the notification
                'type' => [
                    'from_type' => Config::get('opilo_configs.notifications_types.Task'),
                    'to_type' => Config::get('opilo_configs.notifications_types.User')
                ],
                'category' => Config::get('opilo_configs.notifications_categories.Tasks'), // category notification ID
                'url'         => URL::to('task/'.$taskId), // Url of your notification
                'extra'       => Lang::get("tasks.notifications_setAsClose")
            ];

            Notification::sendNotification($sendInformation);
            return true;
        } else
            return false;
    }
}
