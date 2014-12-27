<?php namespace SaleBoss\Services\Letter;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\LetterLogRepositoryInterface;
use SaleBoss\Repositories\LetterRepositoryInterface;

class LetterActionsCommandHandler implements CommandHandler {

    protected $letterRepo;

    /**
     * @var LetterLogRepositoryInterface
     */
    private $letterLogRepo;

    /**
     * @param LetterRepositoryInterface    $letter
     * @param LetterLogRepositoryInterface $letterLogRepository
     */
    function __construct(
        LetterRepositoryInterface $letter,
        LetterLogRepositoryInterface $letterLogRepository
    )
    {
        $this->letterRepo = $letter;
        $this->letterLogRepo = $letterLogRepository;
    }

    public function handle($command)
    {
        if($command->actionType == 1) {
            $letter = $this->letterRepo->setAsDone($command->user, $command->letterId, $command->destinationId);
            $this->letterLogRepo->addLetterLog($command->user, $command->letterId, $command->destinationId, 1, $command->logMessage);
        }
        elseif($command->actionType == 2) {
            $letter = $this->letterRepo->setAsBack($command->user, $command->letterId, $command->destinationId);
            $this->letterLogRepo->addLetterLog($command->user, $command->letterId, $command->destinationId, 2, $command->logMessage);
        }

        return $letter;
    }
}