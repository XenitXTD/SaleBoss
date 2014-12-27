<?php namespace SaleBoss\Services\Folder;

class FolderListCommand {

    public $userGroupId;

    public $userId;

    function __construct($userId, $userGroupId)
    {
        $this->userId = $userId;
        $this->userGroupId = $userGroupId;
    }
}