<?php namespace SaleBoss\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use SaleBoss\Models\TaskMessages;
use SaleBoss\Models\User;
use SaleBoss\Repositories\TaskMessagesRepositoryInterface;
use SaleBoss\Services\Notification\Facades\Notification;

class TaskMessagesRepository extends AbstractRepository implements TaskMessagesRepositoryInterface {

    protected $model;

    function __construct(TaskMessages $taskMsg)
    {
        $this->model = $taskMsg;
    }

    public function sendMessageOnTask(User $user, $taskId, $message, $notification = false, $for_id = null)
    {
        $model = $this->model->newInstance();

        $data = [
            'task_id' => $taskId,
            'creator_id' => $user->getId(),
            'message' => $message
        ];

        $model->task_id = $taskId;
        $model->creator_id = $user->getId();
        $model->message = $message;

        if($notification)
        {
            if($model->save())
            {
                $sendInformation = [
                    'from_id'     => $model->id, // ID user that send the notification
                    'to_id'       => $for_id, // ID user that receive the notification
                    'type' => [
                        'from_type' => Config::get('opilo_configs.notifications_types.TaskMessages'),
                        'to_type' => Config::get('opilo_configs.notifications_types.User')
                    ],
                    'category' => Config::get('opilo_configs.notifications_categories.TaskMessages'), // category notification ID
                    'url'         => URL::to('task/'.$taskId), // Url of your notification
                    'extra'       => Lang::get("tasks.notifications_messageUpdate")
                ];

                Notification::sendNotification($sendInformation);
                return true;
            }
            return false;
        } else
            return $model->save();
    }

    public function setAsRead($user, $taskId)
    {
        $query = $this->model->newInstance();

        $taskMessages = $query->where('task_id', $taskId)->get();

        foreach($taskMessages as $taskMessage)
        {
            Notification::setReadTasksMessagesNotifications($taskMessage->id);
        }

        $query->where('task_id', $taskId)->where('creator_id', '!=', $user->getId())->where('read', 0)->update(['read' => 1]);

        return true;
    }

}