<?php namespace SaleBoss\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SaleBoss\Models\LetterPath;
use SaleBoss\Repositories\GroupRepositoryInterface;
use SaleBoss\Repositories\LetterPathRepositoryInterface;

class LetterPathRepository extends AbstractRepository implements LetterPathRepositoryInterface {

    protected $model;

    /**
     * @var GroupRepositoryInterface
     */
    private $groupRepo;

    /**
     * @param LetterPath               $letterPath
     * @param GroupRepositoryInterface $groupRepository
     */
    function __construct(
        LetterPath $letterPath,
        GroupRepositoryInterface $groupRepository
    )
    {
        $this->model = $letterPath;
        $this->groupRepo = $groupRepository;
    }

    public function addLetterPath($letterId, $path, $pathLog)
    {
        $input = [];
        foreach($path['destination'] as $key => $value) {
            array_push( $input, [
                'letter_id' => $letterId,
                'start' => $path['start'],
                'destination' => $value,
                'prev_place' => $path['start'],
                'current_place' => next($pathLog[$key]),
                'next_place' => has_next(array_values($pathLog[$key]), $pathLog[$key]) ? array_values($pathLog[$key])[2] : Null,
                'path' => json_encode(array_values($pathLog[$key])),
                'created_at' => Carbon::now()->toDateTimeString()
            ]);
        }

        return DB::table($this->model->table)->insert($input);
    }

    public function findByIdAndDestination($letterId, $destinationId)
    {
        return $this->model->newInstance()->where('letter_id', $letterId)->where('destination', $destinationId);
    }
}
