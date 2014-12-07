<?php namespace SaleBoss\Services\Tasks;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\TaskRepositoryInterface;

class TasksIndexCommandHandler implements CommandHandler {

    protected $taskRepo;

    function __construct(
        TaskRepositoryInterface $task
    )
    {
        $this->taskRepo = $task;
    }

    public function handle($command)
    {
        $tasks['forMe'] = $this->taskRepo->getTaskListForMe($command->user);
        $tasks['byMe'] = $this->taskRepo->getTaskListByMe($command->user);
        return $tasks;
    }
}