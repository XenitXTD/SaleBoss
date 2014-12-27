<?php namespace SaleBoss\Services\Folder;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Services\ViewBuilder\Facades\ViewBuilder;

class FolderCreateCommandHandler implements CommandHandler {

    protected $folderRepo;

    function __construct(
        FolderRepositoryInterface $folderRepositoryInterface
    )
    {
        $this->folderRepo = $folderRepositoryInterface;
    }

    public function handle($command)
    {
        return $this->folderRepo->addFolder($command->parent_id, $command->name, $command->creator_id, $command->for_type, $command->for_id);
    }
}