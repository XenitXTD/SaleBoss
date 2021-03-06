<?php namespace SaleBoss\Services\User;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Laracasts\Commander\CommanderTrait;
use SaleBoss\Models\User;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Repositories\OrderRepositoryInterface;
use SaleBoss\Repositories\StateRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Leads\My\Commands\LeadStatisticsCommand;

class Dashboard implements DashboardInterface {

	protected $userRepo;
	protected $user;
	protected $stateRepo;
	protected $orderRepo;
	protected $userQueue;
	protected $typeRepo;
	protected $entityRepo;
	protected $leadRepo;

	use CommanderTrait;

	/**
	 * @TODO too much injection
	 *
	 * @param UserRepositoryInterface $userRepo
	 * @param StateRepositoryInterface $stateRepo
	 * @param OrderRepositoryInterface $orderRepo
	 * @param LeadRepositoryInterface $leadRepo
	 * @param AuthenticatorInterface $auth
	 * @param UserQueue $userQueue
	 */
	public function __construct(
		UserRepositoryInterface $userRepo,
		StateRepositoryInterface $stateRepo,
		OrderRepositoryInterface $orderRepo,
		LeadRepositoryInterface $leadRepo,
		AuthenticatorInterface $auth,
		UserQueue $userQueue
	)
	{
		$this->userRepo = $userRepo;
		$this->orderRepo = $orderRepo;
		$this->stateRepo = $stateRepo;
		$this->userQueue = $userQueue;
		$this->leadRepo = $leadRepo;
		$this->auth = $auth;
	}

	/**
	 * Sets the user that dashboard will be generated based on
	 *
	 * @param User $user
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * The final function that is called from outside
	 *
	 * @return array
	 */
	public function getHisDash(){
		$userQueue = $this->userQueue();
		$generatedUsers = $this->generatedUsers();
		$generatedOrders = $this->generatedOrders();
		$hisSales = $this->hisSales();
		$allOrders = $this->allOrders();
		$openOrders = $this->openOrders();
		$orderChart = $this->orderChart();
		$myLeadStats = $this->ownLeadStats();
        $remindingLeads = $this->getRemindingLeads();
        $leadStats = $this->leadStats();
		return compact (
			'userQueue',
			'generatedOrders',
			'generatedUsers',
			'hisSales',
			'allOrders',
			'openOrders',
			'orderChart',
			'myLeadStats',
            'leadStats',
            'remindingLeads'
		);
	}

	/**
	 * Get user job queue
	 *
	 * @return mixed
	 */
	protected function userQueue()
	{
		$this->userQueue->setUser($this->user);
		return $this->userQueue->summary();
	}

	/**
	 * Get the users that user has created them
	 *
	 * @return Collection
	 */
	protected function generatedUsers()
	{
		return $this->userRepo->getGeneratedUsers($this->user,5);
	}

	/**
	 * Orders Generated by current user
	 *
	 * @return Collection
	 */
	protected function generatedOrders()
	{
		return $this->orderRepo->getGeneratedOrders($this->user, 5);
	}

	/**
	 * Get Final sales of current user
	 *
	 * @return collection
	 */
	protected function hisSales()
	{

	}

	/**
	 * A summary of all orders
	 *
	 * @return Collection
	 */
	protected function allOrders()
	{

	}

	/**
	 * A summary of open orders that are currently on progress
	 *
	 * @return Collection
	 */
	protected function openOrders()
	{

	}

	/**
	 * Provide Chart data
	 *
	 * @return array
	 */
	protected function orderChart()
	{
		$counts =  $this->orderRepo->countableMonthChart()->lists('countable','month');
		$output = [];
		$months = Config::get('jalali_months');
		foreach($months as $key => $month)
		{
			$tmpOutput['date'] = $month;
			$tmpOutput['orders'] = ! empty($counts[$key]) ? $counts[$key] : 0;
			$output[] = $tmpOutput;
		}
		return $output;
	}

	private function ownLeadStats()
	{
		$leadStats = $this->execute(
			LeadStatisticsCommand::class,
			['user' => $this->auth->user(), 'period' => Input::get('period')]
		);
		return $leadStats;
	}

    private function leadStats()
    {

    }

    private function getRemindingLeads()
    {
        return  $this->leadRepo->getRemindableLeads($this->auth->user(), 50);
    }

}
