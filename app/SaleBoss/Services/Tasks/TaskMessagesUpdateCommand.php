<?php namespace SaleBoss\Services\Tasks;

class TaskMessagesUpdateCommand {

    public $user;

    public $taskId;

    public $message;

    public $notification;

    public $for_id;

    /**
     * @param $user
     * @param $taskId
     * @param $message
     */
    function __construct($user, $taskId, $message, $notification = false, $for_id = null)
    {
        $this->user = $user;
        $this->taskId = $taskId;
        $this->message = $message;
        $this->notification = $notification;
        $this->for_id = $for_id;
    }
}