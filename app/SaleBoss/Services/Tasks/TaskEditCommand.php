<?php namespace SaleBoss\Services\Tasks;

class TaskEditCommand {

    public $user;

    public $taskId;

    public $data;

    /**
     * @param $user
     */
    function __construct($user, $taskId, array $data = null)
    {
        $this->user = $user;
        $this->taskId = $taskId;
        $this->data = $data;
    }
}