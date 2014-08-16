<?php namespace SaleBoss\Repositories\Eloquent;

use Carbon\Carbon;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use SaleBoss\Models\Lead;
use SaleBoss\Models\User;
use SaleBoss\Repositories\Exceptions\InvalidArgumentException;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\Exceptions\RepositoryException;
use SaleBoss\Repositories\LeadRepositoryInterface;

class LeadRepository extends AbstractRepository implements LeadRepositoryInterface {

    protected $model;

    /**
     * @param Lead $lead
     */
    public function __construct(Lead $lead)
    {
        $this->model = $lead;
    }

    /**
     * Bulk Lead creation
     *
     * @param array $data
     * @param User  $user
     * @throws \SaleBoss\Repositories\Exceptions\RepositoryException
     * @return mixed
     */
    public function bulkCreate (array $data, User $user = null)
    {
        $data = $this->setCreatorId($data, !empty($user) ? $user->id : null);
        DB::beginTransaction();
           try {
               $this->model->newInstance()->insert($data);
           } catch(QueryException $e) {
               DB::rollback();
               throw new RepositoryException($e->getMessage());
           }
        DB::commit();
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

    public function getUserLeads(User $user, $int = 25)
    {
        return $user->createdLeads()->with('tags','phones')->orderBy('created_at','DESC')->paginate($int);
    }

    public function getUserLeadsBetween(User $user, $todayStart, $todayEnd)
    {
        return $user->createdLeads()
                    ->with('tags','phones')
                    ->whereBetween('remind_at',array($todayStart, $todayEnd))
                    ->get();
    }
}