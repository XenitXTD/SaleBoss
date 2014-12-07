<?php namespace SaleBoss\Services\Tasks;

class TaskActionsCommand {

    public $user;

    public $taskId;

    public $actionType;

    public $for_id;

    /**
     * @param $user
     * @param $taskId
     * @param $message
     */
    function __construct($user, $taskId, $actionType, $for_id = null)
    {
        $this->user = $user;
        $this->taskId = $taskId;
        $this->actionType = $actionType;
        $this->for_id = $for_id;
    }
}