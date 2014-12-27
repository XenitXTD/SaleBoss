<?php namespace SaleBoss\Services\Folder;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Services\ViewBuilder\Facades\ViewBuilder;

class FolderItemsListCommandHandler implements CommandHandler {

    protected $folderRepo;

    protected $list = array();

    function __construct(
        FolderRepositoryInterface $folderRepositoryInterface
    )
    {
        $this->folderRepo = $folderRepositoryInterface;
    }

    public function handle($command)
    {
        return $this->folderRepo->getItemsList($command->userId, $command->folderId);
    }
}