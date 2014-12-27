<?php namespace SaleBoss\Services\Folder;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Services\ViewBuilder\Facades\ViewBuilder;

class FolderItemCreateCommandHandler implements CommandHandler {

    protected $folderRepo;

    function __construct(
        FolderRepositoryInterface $folderRepositoryInterface
    )
    {
        $this->folderRepo = $folderRepositoryInterface;
    }

    public function handle($command)
    {
        return $this->folderRepo->addFolderItem($command->name, $command->creator_id, $command->for_id, $command->description, $command->file);
    }
}