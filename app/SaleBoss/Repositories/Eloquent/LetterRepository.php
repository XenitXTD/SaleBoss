<?php namespace SaleBoss\Repositories\Eloquent;

use Illuminate\Support\Facades\Config;
use SaleBoss\Models\Letter;
use SaleBoss\Repositories\LetterPathRepositoryInterface;
use SaleBoss\Repositories\LetterRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Upload\Facades\Upload;

class LetterRepository extends AbstractRepository implements LetterRepositoryInterface {

    protected $model;

    /**
     * @var LetterPathRepositoryInterface
     */
    private $letterPathRepo;

    /**
     * @var AuthenticatorInterface
     */
    private $auth;

    /**
     * @param Letter                        $letter
     * @param LetterPathRepositoryInterface $letterPathRepository
     * @param AuthenticatorInterface        $authenticator
     */
    function __construct(
        Letter $letter,
        LetterPathRepositoryInterface $letterPathRepository,
        AuthenticatorInterface $authenticator
    )
    {
        $this->model = $letter;
        $this->letterPathRepo = $letterPathRepository;
        $this->auth = $authenticator;
    }

    public function getList($folderId)
    {
        $query = $this->model->newInstance();

        $list = $query->where('folder_id', $folderId)->join('letters_path', 'letters.id', '=', 'letters_path.letter_id')->paginate(25, [
                'letters.id',
                'letters.subject',
                'letters.created_at',
                'letters.folder_id',
                'letters_path.current_place',
                'letters_path.destination',
                'letters_path.start'
            ]);

        return $list;
    }

    public function getMyInbox($userId, $userGroupId)
    {
        $query = $this->model->newInstance();

        $list = $query->join('letters_path', 'letters.id', '=', 'letters_path.letter_id')->where('current_place', $userGroupId)->paginate(25, [
                'letters.id',
                'letters.subject',
                'letters.created_at',
                'letters.folder_id',
                'letters_path.current_place',
                'letters_path.destination'
            ]);

        return $list;
    }

    public function getMyOutbox($userId, $userGroupId)
    {
        $query = $this->model->newInstance();

        $list = $query->join('letters_path', 'letters.id', '=', 'letters_path.letter_id')->where('letters_path.start', $userGroupId)->paginate(25, [
                'letters.id',
                'letters.subject',
                'letters_path.created_at',
                'letters.folder_id',
                'letters_path.letter_id',
                'letters_path.start',
                'letters_path.destination'
            ]);

        return $list;
    }

    public function addLetter($subject, $message, $userId, $folder, $path, $file, $pathLog)
    {
        $model = $this->model->newInstance();

        $model->subject = $subject;
        $model->message = $message;
        $model->creator_id = $userId;
        $model->folder_id = $folder;

        if($model->save())
        {
            if(!is_null($file))
            {
                Upload::doUpload($file,
                                 [
                                     'for_id'   => $model->id,
                                     'for_type' => Config::get('opilo_configs.notifications_types.Letter'),
                                     'path'     =>  'files/letters/'.$model->id
                                 ]);
            }

            $this->letterPathRepo->addLetterPath($model->id, $path, $pathLog);
        }

        return true;
    }

    public function findById($id)
    {
        return $this->model->newInstance()->findOrFail($id)->first();
    }

    public function setAsDone($user, $letterId, $destinationId)
    {
        $letterPath = $this->letterPathRepo->findByIdAndDestination($letterId, $destinationId);

        $key = array_search($letterPath->first()->next_place, array_values(json_decode($letterPath->first()->path)));

        $prev_place = $letterPath->first()->current_place;
        $current_place = $letterPath->first()->next_place;
        $next_place = has_next(array_values(json_decode($letterPath->first()->path)), $letterPath->first()->next_place) ? json_decode($letterPath->first()->path)[$key + 1] : Null ;

        return $letterPath->update(
            [
                'prev_place' => $prev_place,
                'current_place' => $current_place,
                'next_place' => $next_place
            ]
        );
    }

    public function setAsBack($user, $letterId, $destinationId)
    {
        $letterPath = $this->letterPathRepo->findByIdAndDestination($letterId, $destinationId);

        $key = array_search($letterPath->first()->prev_place, array_values(json_decode($letterPath->first()->path)));

        $prev_place = has_prev(array_values(json_decode($letterPath->first()->path)), $letterPath->first()->prev_place) ? json_decode($letterPath->first()->path)[$key - 1] : Null ;
        $current_place = $letterPath->first()->prev_place;
        $next_place = $letterPath->first()->current_place;

        return $letterPath->update(
            [
                'prev_place' => $prev_place,
                'current_place' => $current_place,
                'next_place' => $next_place
            ]
        );
    }

    public function findByIdAndDestination($id, $toId)
    {
        $query = $this->model->newInstance();

        $list = $query->Join('letters_path', 'letters.id', '=', 'letters_path.letter_id')
            ->where('letters.id', $id)
            ->where('letters_path.destination', $toId)
            ->first();

        return $list;
    }

    public function getSearch($input)
    {
        $query = $this->model->newInstance();

        $query = $query->distinct()->Join('letters_path', 'letters.id', '=', 'letters_path.letter_id');

        if(!is_null($input))
        {
            foreach($input as $field => $value)
            {
                if($field == 'id')
                    $field = 'letters_path.letter_id';

                if(!is_null($field) and !empty($value))
                {
                    if($field == 'destination' or $field == 'start')
                        $query = $query->where($field, $value);
                    else
                        $query = $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        } else
            $query = $query->where('letters_path.start', $this->auth->user()->getId());

        return $query->groupBy('letters_path.letter_id')->paginate(50, [
                'letters.id',
                'letters.subject',
                'letters.created_at',
                'letters.folder_id',
                'letters.message',
                'letters_path.destination',
                'letters_path.start'
            ]);
    }

    public function changeForDeleteFolder($folderId)
    {
        $model = $this->model->newInstance();

        $model = $model->where('folder_id', $folderId);

        return $model->update(
            [
                'folder_id' => Null
            ]
        );
    }
}
