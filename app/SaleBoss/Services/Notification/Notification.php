<?php
namespace SaleBoss\Services\Notification;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use SaleBoss\Repositories\Eloquent\NotificationRepository;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;

class Notification implements NotificationInterface {

	protected $userRepo;
	protected $leadRepo;
	protected $notifications;
	protected $auth;

	/**
	 * @param UserRepositoryInterface                                $userRepo
	 * @param LeadRepositoryInterface                                $leadRepo
	 * @param AuthenticatorInterface                                 $auth
	 * @param NotificationRepository|NotificationRepositoryInterface $notifications
	 */

	public function __construct(
		UserRepositoryInterface $userRepo,
		LeadRepositoryInterface $leadRepo,
		AuthenticatorInterface $auth,
		NotificationRepository $notifications
	)
	{
		$this->userRepo = $userRepo;
		$this->leadRepo = $leadRepo;
		$this->auth = $auth;
		$this->notificationsRepo = $notifications;
	}

	/**
	 * This method write requests from blade to it every time.
	 * @param $view
	 */
	public function compose($view)
    {
	    $view->with('notifications', null);
	    $view->with(
		    array(
			    'TodayLeadsNotificationsCount' => $this->getTodayNotReadLeadsNotificationsCount(),
			    'TodayLeadsNotificationsList' => $this->getTodayNotReadLeadsNotifications(),
		    )
	    );
    }

	/**
	 * Set new notification row for today remindable leads
	 * @param Number of today reminding leads $count
	 * @return mixed
	 */
	public function setTodayNotReadLeadsNotification($count)
	{
		$data = [
			'from_id' => 0,
			'to_id' => $this->auth->user()->getId(),
		    'category' => Config::get('saleboss\opilo_configs.notifications_categories.TodayLeads'),
		    'type' => [
			    'from_type' => Config::get('saleboss\opilo_configs.notifications_types.Lead'),
			    'to_type' => Config::get('saleboss\opilo_configs.notifications_types.User')
		    ],
		    'url' => URL::route('LeadsUnreads'),
		    'count' => $count
		];

		Session::put('TodayLeadsNotifications', true); // Set Session for flash notification message just for once

		return $this->notificationsRepo->sendNotification($data);
	}

	/**
	 * Get today user remindable leads by count
	 * @return mixed
	 */
	private function getTodayNotReadLeadsNotificationsCount()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('saleboss\opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('saleboss\opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('saleboss\opilo_configs.notifications_types.User')
			],
		    'limit' => null,
		    'paginate' => false,
		    'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
		    'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];
		return $this->notificationsRepo->getNotReadNotifications($data)->first();
	}

	/**
	 * Get today user remindable leads by count
	 * @return mixed
	 */
	private function getTodayNotReadLeadsNotifications()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('saleboss\opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('saleboss\opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('saleboss\opilo_configs.notifications_types.User')
			],
			'limit' => null,
			'paginate' => false,
			'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
			'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];
		return $this->notificationsRepo->getNotReadNotifications($data);
	}

	/**
	 * Check if user has another today notification or not
	 * Used on LeadRepository and if it has false return, Event 'notifications.todayleads.unread' will fired.
	 * @return bool
	 */
	public function checkTodayLeadsIsExist()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('saleboss\opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('saleboss\opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('saleboss\opilo_configs.notifications_types.User')
			],
			'limit' => null,
			'paginate' => false,
			'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
			'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];

		$exist = $this->notificationsRepo->getNotificationsCount($data) ? true : false;
		return $exist;
	}

	/**
	 * Change all leads notification to 'Read'
	 * @return mixed
	 */
	public function setReadAllLeadsNotifications()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('saleboss\opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('saleboss\opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('saleboss\opilo_configs.notifications_types.User')
			],
			'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
			'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];

		return $this->notificationsRepo->setReadNotifications($data);
	}

}