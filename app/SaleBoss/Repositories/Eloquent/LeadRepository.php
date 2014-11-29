<?php namespace SaleBoss\Repositories\Eloquent;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use SaleBoss\Models\Lead;
use SaleBoss\Models\User;
use SaleBoss\Repositories\Exceptions\InvalidArgumentException;
use SaleBoss\Repositories\Exceptions\RepositoryException;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Repositories\PhoneRepositoryInterface;
use SaleBoss\Repositories\TagRepositoryInterface;
use SaleBoss\Services\Notification\Facades\Notification;
use Whoops\Example\Exception;

class LeadRepository extends AbstractRepository implements LeadRepositoryInterface {

    protected $model;

    /**
     * @var PhoneRepositoryInterface
     */
    private $phones;

    /**
     * @var TagRepositoryInterface
     */
    private $tagRepo;

    /**
     * @param Lead $lead
     */
    public function __construct(Lead $lead, PhoneRepositoryInterface $phones, TagRepositoryInterface $tagRepo)
    {
        $this->model = $lead;
        $this->phones = $phones;
        $this->tagRepo = $tagRepo;
    }

    /**
     * Bulk Lead creation
     *
     * @param array $data
     * @param User  $user
     * @throws \SaleBoss\Repositories\Exceptions\RepositoryException
     * @return mixed
     */
    public function bulkCreate(array $data, User $user = null)
    {
//        $data = $this->setCreatorId($data, !empty($user) ? $user->id : null);
           try {
                DB::beginTransaction();
               $all_new_creator_ids = array_unique(array_column($data, 'creator_id'));
               $this->model->where('new_lead','=', '1')->whereIn('creator_id', $all_new_creator_ids)->update(['new_lead'=> null]);
               foreach($data as $lead_data)
               {
                   $phone = $lead_data['phone_number'];
                   $tag_id = $lead_data['tag_id'];
                   unset($lead_data['phone_number']);
                   unset($lead_data['tag_id']);
                   $lead = $this->model->create($lead_data);
                   $this->phones->addPhoneToLead($lead, $phone);
                   $this->tagRepo->addTagToLead($lead, $this->tagRepo->findById($tag_id));
               }
                DB::commit();
           } catch(Exception $e) {

               DB::rollback();
               throw new RepositoryException($e->getMessage());
           }
    }

    /**
     * Set creator id on data
     *
     * @param array $data
     * @param       $creator_id
     * @return array
     */
    private function setCreatorId(array $data, $creator_id)
    {
        foreach($data as &$item)
        {
            $item['creator_id'] = $creator_id;
        }
        return $data;
    }


    /**
     * Count available leads
     *
     *
     * @return int
     */
    public function count ()
    {
        return $this->model->newInstance()->count();
    }

    /**
     * Update a lead
     *
     * @param \SaleBoss\Models\Lead $lead
     * @param array                 $data
     * @throws \SaleBoss\Repositories\Exceptions\InvalidArgumentException
     * @return mixed
     */
    public function update (Lead $lead, array $data = [])
    {
        if (empty($data)){
            $lead->save();
            return $lead;
        }
        try {
            $lead->update($data);
            return $lead;
        }catch (QueryException $e){
            throw new InvalidArgumentException($e->getMessage());
        }
    }

	/**
	 * @author bigsinoos <pcfeeler@gmail.com>
	 * Add relationships to a lead
	 *
	 * @param Lead $lead
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getAllForLead(Lead $lead)
	{
		return $lead->load('phones','tags');
	}

    /**
     * @author bigsinoos <pcfeeler@gmail.com>
     * @edit saeedhbi   <saeedhbi@live.com>
     * Get user leads
     * @param User $user
     * @param int  $int
     * @return mixed
     */
	public function getUserLeads(User $user, $int = 25)
    {
        return $user->createdLeads()->with('tags','phones')->orderBy('created_at','DESC')->paginate($int);
    }

	/**
     * Get User Leads Paginate Between Two Time
     * @param User $user
     * @param First Time of Leads $firstTime
     * @param Second Time of Leads $secondTime
     * @param int  $int
     * @return mixed
     */
    public function getUserLeadByTime(User $user, $firstTime = null, $secondTime = null, $int = 25)
    {
        $q = $user->createdLeads()->with('tags','phones');
        if(!empty($firstTime) or !empty($secondTime)) {
            $q = $q->whereBetween('created_at', [$firstTime, $secondTime]);
        }
        return $q->orderBy('created_at','DESC')->paginate($int);
    }

    /**
     * Get All Leads Paginate
     *
     * @return Collection
     */
    public function getAllLeadPaginated($int = 25)
    {
        return $this->model->with('creator')->orderBy('created_at','DESC')->paginate($int);
    }


	/**
	 * @author bigsinoos <pcfeeler@gmail.com>
	 * Get user leads between a date
	 *
	 * @param User $user
	 * @param $todayStart
	 * @param $todayEnd
	 * @return mixed
	 */
	public function getUserLeadsBetween(User $user, $todayStart, $todayEnd)
    {
        $todayStart = Carbon::createFromTimestamp($todayStart)->toDateString();
        $todayEnd = Carbon::createFromTimestamp($todayEnd)->toDateString();
        return $user->createdLeads()
                    ->with('tags','phones')
                    ->whereBetween('remind_at',[$todayStart, $todayEnd])
                    ->get();
    }

	public function getCountableStatuses(User $user, $before = null)
	{
		if (is_null($before))
		{
			return $user->createdLeads()
				        ->getQuery()
						->groupBy('status')
						->get(['status', DB::raw('count(*) as total')]);
		}
        $before = Carbon::createFromTimestamp((int) $before);
		return $user->createdLeads()
					->getQuery()
					->where('created_at','>', $before->toDateString())
					->groupBy('status')
					->get(['status', DB::raw('count(*) as total')]);
	}

    public function getRemindableLeads(User $user, $nextDay, $int = 50)
    {
        $todayStart = Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString();
        $nextDay2 = Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays($nextDay)->toDateTimeString();

        $leads = $user->createdLeads()->whereBetween('remind_at', [$todayStart, $nextDay2])
                    ->with('tags','phones')
                    ->orderBy('remind_at','ASC')
                    ->take($int)->get();

        return $leads;

    }

    public function getTodayRemindingLeadsCount(User $user)
    {
        $todayStart = Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString();
        $nextDay = Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString();

        $leads =  $user->createdLeads()->whereBetween('remind_at', [$todayStart, $nextDay])
                      ->with('tags','phones')
                      ->orderBy('remind_at','ASC')
                      ->get()->count();

        if($leads and !Notification::checkTodayLeadsIsExist())
            Event::fire('notifications.todayleads.unread', $leads);
        return $leads;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserAllLeads(User $user)
    {
        return $user->createdLeads()->with('tags','phones')->count();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function getUserAllLeadsApproved(User $user)
    {
        return $user->createdLeads()->where('status','1')->count();
    }



}
