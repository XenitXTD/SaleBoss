<?php namespace SaleBoss\Events;

use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Notification\Notification as NotificationService;
use Symfony\Component\EventDispatcher;

class NotificationEventHandler {

    protected $notification;
    protected $userRepo;

    /**
     * @param NotificationService $notification
     * @param UserRepositoryInterface $user
     */
    public function __construct(NotificationService $notification, UserRepositoryInterface $user)
    {
        $this->notification = $notification;
        $this->userRepo = $user;
    }

	/**
     * Change Leads Status to Read when event 'notifications.todayleads.read' is fired
     */
    public function onLeadsRead()
    {
        $this->notification->setReadAllLeadsNotifications();
    }

	/**
     * Event Handler for Notifications
     * Its sync with Notifynder Event Handler
     * @param $events Event Name
     */
    public function subscribe($events)
    {
        $events->listen('notifications.todayleads.read', 'SaleBoss\Events\NotificationEventHandler@onLeadsRead');
        $events->listen('notifications.todayleads.unread', function($count)
        {
            $this->notification->setTodayNotReadLeadsNotification($count);
        });
    }

}