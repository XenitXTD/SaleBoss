<?php namespace SaleBoss\Services\Tasks;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\TaskMessagesRepositoryInterface;

class TaskMessagesUpdateCommandHandler implements CommandHandler {

    protected $taskMsgRepo;

    function __construct(
        TaskMessagesRepositoryInterface $taskMsg
    )
    {
        $this->taskMsgRepo = $taskMsg;
    }

    public function handle($command)
    {
        $task = $this->taskMsgRepo->sendMessageOnTask($command->user, $command->taskId, $command->message, $command->notification, $command->for_id);
        return $task;
    }
}