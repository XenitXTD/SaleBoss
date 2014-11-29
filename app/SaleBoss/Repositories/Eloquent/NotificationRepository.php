<?php
namespace SaleBoss\Repositories\Eloquent;

use Fenos\Notifynder\Notifications\Repositories\NotificationRepository as ExNotificationRepository;
use Fenos\Notifynder\Facades\Notifynder;
use Illuminate\Support\Facades\URL;
use SaleBoss\Models\Notification;
use SaleBoss\Models\User;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;

class NotificationRepository extends AbstractRepository {

    protected $notificationRepo;
    protected $userRepo;
    protected $auth;
    protected $lead;

    /**
     * @param ExNotificationRepository|Notification $notification
     * @param UserRepositoryInterface               $user
     * @param LeadRepositoryInterface               $lead
     * @internal param AuthenticatorInterface $auth
     */
    public function __construct(
        ExNotificationRepository $notification,
        UserRepositoryInterface $user,
        LeadRepositoryInterface $lead
    )
    {
        $this->notificationRepo = $notification;
        $this->userRepo = $user;
        $this->leadRepo = $lead;
    }

	/**
     * Find 'Not Read' notifications by user request as count
     * @param array $data
     * @return mixed
     */
    public function getNotReadNotificationsCount(array $data)
    {
        $to_id = $data['to_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $limit = $data['limit'];
        $paginate = $data['paginate'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::getNotReadNotifications($to_id, $category, $type, $first_time, $second_time, $limit, $paginate)->count();
    }

	/**
     * Find 'Not Read' notifications by user request as object
     * @param array $data
     * @return mixed
     */
    public function getNotReadNotifications(array $data)
    {
        $to_id = $data['to_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $limit = $data['limit'];
        $paginate = $data['paginate'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::getNotReadNotifications($to_id, $category, $type, $first_time, $second_time, $limit, $paginate);
    }

	/**
     * Find 'All' notifications by user request as count
     * @param array $data
     * @return mixed
     */
    public function getNotificationsCount(array $data)
    {
        $to_id = $data['to_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $limit = $data['limit'];
        $paginate = $data['paginate'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::getNotifications($to_id, $category, $type, $first_time, $second_time, $limit, $paginate)->count();
    }

	/**
     * Find 'All' notifications by user request as object
     * @param array $data
     * @return mixed
     */
    public function getNotifications(array $data)
    {
        $to_id = $data['to_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $limit = $data['limit'];
        $paginate = $data['paginate'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::getNotifications($to_id, $category, $type, $first_time, $second_time, $limit, $paginate);
    }

	/**
     * Change 'All' notifications to 'Read' status
     * @param array $data
     * @return mixed
     */
    public function setReadNotifications(array $data)
    {
        $to_id = $data['to_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::setReadNotifications($to_id, $category, $type, $first_time, $second_time);
    }

	/**
     * Create new notification by user request
     * Return Notifyunder method
     * @param array $data
     * @return mixed
     */
    public function sendNotification(array $data)
    {
        $sendInformation = [
            'from_id'     => $data['from_id'], // ID user that send the notification
            'from_type'   => $data['type']['from_type'],
            'to_id'       => $data['to_id'], // ID user that receive the notification
            'to_type'     => $data['type']['to_type'],
            'category_id' => Notifynder::category($data['category'])->id(), // category notification ID
            'url'         => $data['url'], // Url of your notification
            'extra'       => $data['count']
        ];

        return Notifynder::send($sendInformation);
    }

}
