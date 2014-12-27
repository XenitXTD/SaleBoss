<?php namespace SaleBoss\Repositories\Eloquent;

use SaleBoss\Models\LetterLog;
use SaleBoss\Models\User;
use SaleBoss\Repositories\LetterLogRepositoryInterface;
use SaleBoss\Repositories\LetterPathRepositoryInterface;

class LetterLogRepository extends AbstractRepository implements LetterLogRepositoryInterface {

    protected $model;

    /**
     * @var LetterPathRepository
     */
    private $letterPathRepo;

    /**
     * @param LetterLog                     $letterLog
     * @param LetterPathRepositoryInterface $letterPathRepository
     */
    function __construct(
        LetterLog $letterLog,
        LetterPathRepositoryInterface $letterPathRepository
    )
    {
        $this->model = $letterLog;
        $this->letterPathRepo = $letterPathRepository;
    }

    public function addLetterLog(User $user, $letterId, $destinationId, $logType, $message = null)
    {
        $letterPath = $this->letterPathRepo->findByIdAndDestination($letterId, $destinationId)->first();

        $model = $this->model->newInstance();

        $model->letter_id = $letterId;
        $model->creator_id = $user->getId();
        $model->path_id = $letterPath->id;
        $model->log_type = $logType;
        $model->message = $message;

        if($model->save()) return true;
    }

    public function getList($letterId, $pathId)
    {
        $query = $this->model->newInstance();

        return $query->where('letter_id', $letterId)->where('path_id', $pathId)->get();
    }
}
