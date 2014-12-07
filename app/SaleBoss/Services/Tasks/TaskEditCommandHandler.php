<?php namespace SaleBoss\Services\Tasks;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\TaskRepositoryInterface;

class TaskEditCommandHandler implements CommandHandler {

    protected $taskRepo;

    function __construct(
        TaskRepositoryInterface $task
    )
    {
        $this->taskRepo = $task;
    }

    public function handle($command)
    {
        if($command->data)
        {
            $task = $this->taskRepo->editTask($command->user, $command->taskId, $command->data);

        } else {
            $task = $this->taskRepo->showTask($command->user, $command->taskId);
        }

        $showTask = $this->checkShowingTask($task, $command);

        return $showTask;
    }

    private function checkShowingTask($task, $command)
    {
        if ($task->creator_id == $command->user->getId()) {
                return $task;
        } else
            $task = false;
            return $task;
    }
}