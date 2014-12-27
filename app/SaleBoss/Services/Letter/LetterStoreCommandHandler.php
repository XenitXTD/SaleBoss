<?php namespace SaleBoss\Services\Letter;

use Laracasts\Commander\CommandHandler;
use SaleBoss\Repositories\LetterPathRepositoryInterface;
use SaleBoss\Repositories\LetterRepositoryInterface;

class LetterStoreCommandHandler implements CommandHandler {

    /**
     * @var LetterRepositoryInterface
     */
    private $letterRepo;

    /**
     * @var LetterPathRepositoryInterface
     */
    private $letterPathRepo;

    /**
     * @var LetterPathHandler
     */
    private $letterPathHandler;

    /**
     * @param LetterRepositoryInterface     $letterRepositore
     * @param LetterPathRepositoryInterface $letterPathRepository
     * @param LetterPathHandler             $letterPathHandler
     */
    function __construct(
        LetterRepositoryInterface $letterRepositore,
        LetterPathRepositoryInterface $letterPathRepository,
        LetterPathHandler $letterPathHandler
    )
    {
        $this->letterRepo = $letterRepositore;
        $this->letterPathRepo = $letterPathRepository;
        $this->letterPathHandler = $letterPathHandler;
    }

    /**
     * @param $command
     * @return mixed|void
     */
    public function handle($command)
    {
        foreach ($command->path['destination'] as $destination)
        {
            $value[] = $this->letterPathHandler->resetOutput()->createPath($command->path['start'], $destination);
        }

        if($value)
        {
            return $this->letterRepo->addLetter($command->subject, $command->message, $command->user, $command->folder, $command->path, $command->file, $value);
        }
    }
}