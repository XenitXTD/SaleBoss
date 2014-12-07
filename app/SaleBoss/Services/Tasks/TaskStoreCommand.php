<?php namespace SaleBoss\Services\Tasks;

class TaskStoreCommand {

    public $user;

    public $data;

    /**
     * @param $user
     */
    function __construct($user ,array $data)
    {
        $this->user = $user;
        $this->data = $data;
    }
}