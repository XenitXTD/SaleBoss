<?php namespace SaleBoss\Services\Folder;

class FolderItemsListCommand {

    public $folderId;

    public $userId;

    function __construct($userId, $folderId)
    {
        $this->userId = $userId;
        $this->folderId = $folderId;
    }
}