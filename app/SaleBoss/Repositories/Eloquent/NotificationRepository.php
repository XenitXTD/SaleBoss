<?php
namespace SaleBoss\Repositories\Eloquent;

use Fenos\Notifynder\Notifications\Repositories\NotificationRepository as ExNotificationRepository;
use Fenos\Notifynder\Facades\Notifynder;
use SaleBoss\Models\Notification;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;

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
        $from_id = empty($data['from_id']) ? null : $data['from_id'];
        $category = Notifynder::category($data['category'])->id();
        $type = $data['type'];
        $first_time = $data['first_time'] ? $data['first_time'] : null;
        $second_time = $data['second_time'] ? $data['second_time'] : null;

        return Notification::setReadNotifications($to_id, $category, $type, $from_id, $first_time, $second_time);
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
            'extra'       => $data['extra']
        ];

        return Notifynder::send($sendInformation);
    }

    /**
     * Create new multiplte notification by user request
     * Return Notifyunder method
     *
     * @param array $data
     * @param array $senders
     * @return mixed
     */
    public function sendMultipleNotification(array $data, array $senders)
    {
        $sendInformation = Notifynder::builder()->loop($senders,function($builder,$key,$senders) use($data){
                return $builder->from($data['type']['from_type'], $senders)
                               ->to($data['type']['to_type'], $data['to_id'])
                               ->category($data['category'])
                               ->url($data['url']);
        });

        return Notifynder::sendMultiple($sendInformation);
    }

	/**
     * Delete Single Notification by ID
     *
     * @param array $data
     */
    public function deleteSingleNotification($data)
    {
        return Notifynder::delete($data);
    }

	/**
     * Delete User Notifications by user id
     *
     * @param array $data
     */
    public function deleteUserNotifications($data)
    {
       return Notifynder::deleteAll($data);
    }

	/**
     * Delete a Notification by From Id
     * @param $data
     */
    public function deleteNotificationByFrom($data)
    {
        return Notification::deleteByFrom($data);
    }

}
