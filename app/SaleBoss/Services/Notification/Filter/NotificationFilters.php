<?php namespace SaleBoss\Services\Notification\Filter;

use Illuminate\Support\Facades\Session;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;

class NotificationFilters {

    protected $lead;
    protected $auth;

    function __construct(
        LeadRepositoryInterface  $lead,
        AuthenticatorInterface $auth
    )
    {
        $this->lead = $lead;
        $this->auth = $auth;
    }

    public function filter()
    {
        $this->TodayRemindingLeadsCount();
    }

    private function TodayRemindingLeadsCount()
    {
        return $this->lead->getTodayRemindingLeadsCount($this->auth->user());
    }

}