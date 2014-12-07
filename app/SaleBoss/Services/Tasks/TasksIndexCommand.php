<?php namespace SaleBoss\Services\Tasks;

class TasksIndexCommand {

    public $user;

    /**
     * @param $user
     */
    function __construct($user)
    {
        $this->user = $user;
    }
}