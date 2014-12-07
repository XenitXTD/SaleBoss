<?php namespace SaleBoss\Services\Tasks;

class TaskShowCommand {

    public $user;

    public $taskId;

    /**
     * @param $user
     */
    function __construct($user, $taskId)
    {
        $this->user = $user;
        $this->taskId = $taskId;
    }
}