<?php namespace SaleBoss\Repositories;

use Illuminate\Database\Eloquent\Model;
use SaleBoss\Models\Lead;
use SaleBoss\Models\User;

interface LeadRepositoryInterface {
    /**
     * Bulk Lead creation
     *
     * @param array $data
     * @param User  $user
     * @return mixed
     */
    public function bulkCreate(array $data, User $user = null);


    /**
     * Count available leads
     *
     *
     * @return int
     */
    public function count ();

    /**
     * Update a lead
     *
     * @param \SaleBoss\Models\Lead $lead
     * @param array                 $data
     * @return mixed
     */
    public function update (Lead $lead, array $data =[]);



	/**
	 * @author bigsinoos <pcfeeler@gmail.com>
	 * Create lead from raw unfiltered data
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function createRaw(array $data);

	public function getAllForLead(Lead $lead);

    public function getUserLeads(User $user, $int);

    public function getUserLeadByTime(User $user, $firstTime = null, $secondTime = null, $int);

    public function getUserLeadsBetween(User $user, $todayStart, $todayEnd);

	public function deleteByModel(Model $model);

	public function deleteById($id);

	public function getCountableStatuses(User $user, $before = null);

    public function getRemindableLeads(User $user, $nextDay, $int = 50);

    public function getTodayRemindingLeadsCount(User $user);

    public function getUserAllLeads(User $user);

    public function getUserAllLeadsApproved(User $user);

    public function countAll($firstTime, $secondTime);
} 
