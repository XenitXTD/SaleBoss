<?php namespace SaleBoss\Services\Folder;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Services\ViewBuilder\Facades\ViewBuilder;

class FolderListCommandHandler implements CommandHandler {

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
        $list['Group'] = $this->folderRepo->getList('SaleBoss\Models\Group', $command->userGroupId);

        $list['User'] = $this->folderRepo->getList('SaleBoss\Models\User', $command->userId);

        return $list;
    }
}