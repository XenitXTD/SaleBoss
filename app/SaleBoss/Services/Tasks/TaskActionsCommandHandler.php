<?php namespace SaleBoss\Services\Tasks;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\TaskRepositoryInterface;

class TaskActionsCommandHandler implements CommandHandler {

    protected $taskRepo;

    function __construct(
        TaskRepositoryInterface $task
    )
    {
        $this->taskRepo = $task;
    }

    public function handle($command)
    {
        if($command->actionType == 0) {
            $task = $this->taskRepo->setAsToBeDone($command->user, $command->taskId);
        }
        elseif($command->actionType == 1) {
            $task = $this->taskRepo->setAsDone($command->user, $command->taskId, $command->for_id);
        }
        elseif($command->actionType == 2) {
            $task = $this->taskRepo->setAsClose($command->user, $command->taskId);
        }

        return $task;
    }
}