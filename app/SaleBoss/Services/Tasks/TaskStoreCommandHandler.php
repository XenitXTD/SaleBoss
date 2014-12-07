<?php namespace SaleBoss\Services\Tasks;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\TaskRepositoryInterface;

class TaskStoreCommandHandler implements CommandHandler {

    protected $taskRepo;

    function __construct(
        TaskRepositoryInterface $task
    )
    {
        $this->taskRepo = $task;
    }

    public function handle($command)
    {
        return $this->taskRepo->createTask($command->user, $command->data);
    }
}