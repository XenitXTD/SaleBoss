<?php namespace SaleBoss\Services\Letter;

class LetterListCommand {

    public $folderId;

    public $userGroupId;

    public $userId;

    function __construct($folderId, $userId, $userGroupId)
    {
        $this->folderId = $folderId;

        $this->userId = $userId;

        $this->userGroupId = $userGroupId;
    }
}