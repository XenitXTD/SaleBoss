<?php namespace SaleBoss\Services\Letter;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Models\Group;
use SaleBoss\Models\User;
use SaleBoss\Repositories\FolderRepositoryInterface;
use SaleBoss\Repositories\LetterRepositoryInterface;

class LetterListCommandHandler implements CommandHandler {

    protected  $letterRepo;

    /**
     * @var FolderRepositoryInterface
     */
    private $folderRepo;

    function __construct(
        LetterRepositoryInterface $letterRepositoryInterface,
        FolderRepositoryInterface $folderRepositoryInterface
    )
    {
        $this->letterRepo = $letterRepositoryInterface;

        $this->folderRepo = $folderRepositoryInterface;
    }

    /**
     * @param LetterListCommand $command
     * @throws \Exception
     * @return mixed
     */
    public function handle($command)
    {
        $folder = $this->folderRepo->getById($command->folderId);

        if (
            ($folder->for_type == Group::class && $folder->for_id == $command->userGroupId) ||
            ($folder->for_type == User::class && $folder->for_id == $command->userId)
        ) {
            return $this->letterRepo->getList($command->folderId);
        }
        throw new \Exception('no access');

    }
}