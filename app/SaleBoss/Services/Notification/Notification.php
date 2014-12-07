<?php
namespace SaleBoss\Services\Notification;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
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
		    [
			    'TodayLeadsNotificationsCount' => $this->getTodayNotReadLeadsNotificationsCount(),
		        'tasks'                        => $this->getMyNotReadTasksNotifications(),
		        'tasksMessages'                => $this->getMyNotReadTasksMessagesNotifications()
		    ]
	    );
    }

	/**
	 * Set new notification row for today remindable leads
	 * @param Number of today reminding leads $count
	 * @return mixed
	 */
	public function setTodayNotReadLeadsNotifications($senders)
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
		    'category' => Config::get('opilo_configs.notifications_categories.TodayLeads'),
		    'type' => [
			    'from_type' => Config::get('opilo_configs.notifications_types.Lead'),
			    'to_type' => Config::get('opilo_configs.notifications_types.User')
		    ],
		    'url' => URL::route('LeadsUnreads')
		];

		Session::put('TodayLeadsNotifications', true); // Set Session for flash notification message just for once

		return $this->notificationsRepo->sendMultipleNotification($data, $senders);
	}

	/**
	 * Get today user remindable leads by count
	 * @return mixed
	 */
	private function getTodayNotReadLeadsNotificationsCount()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
		    'limit' => null,
		    'paginate' => false,
		    'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
		    'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];
		return $this->notificationsRepo->getNotReadNotificationsCount($data);
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
			'category' => Config::get('opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
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
			'category' => Config::get('opilo_configs.notifications_categories.TodayLeads'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.Lead'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
			'first_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->toDateTimeString(),
			'second_time' => Carbon::createFromTimestamp(strtotime('tomorrow') - (24 * 60 * 60))->setTime(0,0,0)->addDays(1)->toDateTimeString()
		];

		return $this->notificationsRepo->setReadNotifications($data);
	}

	/**
	 * Update Notifications when a lead is deleted
	 *
	 * @param $data
	 */
	public function updateNotificationsOnLeadDelete($data)
	{
		$this->notificationsRepo->deleteNotificationByFrom($data);

	}

	/**
	 * Call Notification Service from another services
	 *
	 * @param $data
	 */
	public function sendNotification($data)
	{
		$sendInformation = [
			'from_id'     => $data['from_id'], // ID user that send the notification
			'type' => [
				'from_type' => $data['type']['from_type'],
				'to_type' => $data['type']['to_type']
			],
			'to_id'       => $data['to_id'], // ID user that receive the notification
			'category'    => $data['category'], // category notification ID
			'url'         => $data['url'], // Url of your notification
			'extra'       => $data['extra']
		];

		return $this->notificationsRepo->sendNotification($sendInformation);
	}

	private function getMyNotReadTasksNotifications()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('opilo_configs.notifications_categories.Tasks'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.Task'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
			'limit' => null,
			'paginate' => false,
			'first_time' => null,
			'second_time' => null
		];

		return $this->notificationsRepo->getNotReadNotifications($data);
	}

	private function getMyNotReadTasksMessagesNotifications()
	{
		$data = [
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('opilo_configs.notifications_categories.TaskMessages'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.TaskMessages'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
			'limit' => null,
			'paginate' => false,
			'first_time' => null,
			'second_time' => null
		];

		return $this->notificationsRepo->getNotReadNotifications($data);
	}

	public function setReadTasksNotifications($id)
	{
		$data = [
			'from_id' => $id,
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('opilo_configs.notifications_categories.Tasks'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.Task'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
			'first_time' => null,
			'second_time' => null
		];

		return $this->notificationsRepo->setReadNotifications($data);
	}

	public function setReadTasksMessagesNotifications($id)
	{
		$data = [
			'from_id' => $id,
			'to_id' => $this->auth->user()->getId(),
			'category' => Config::get('opilo_configs.notifications_categories.TaskMessages'),
			'type' => [
				'from_type' => Config::get('opilo_configs.notifications_types.TaskMessages'),
				'to_type' => Config::get('opilo_configs.notifications_types.User')
			],
			'first_time' => null,
			'second_time' => null
		];

		return $this->notificationsRepo->setReadNotifications($data);
	}

}