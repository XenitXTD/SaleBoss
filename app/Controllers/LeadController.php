<?php namespace Controllers;

use Illuminate\Support\Facades\Event;
use Miladr\Jalali;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\LeadRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\Leads\Creator\CreatorInterface;
use SaleBoss\Services\Leads\Creator\CreatorListenerInterface;
use SaleBoss\Services\Leads\My\Commands\ListCommand;
use SaleBoss\Services\Leads\My\Commands\ListWithTimeCommand;
use SaleBoss\Services\Leads\Presenter\DelegateManInterface;
use SaleBoss\Services\Leads\Presenter\PickerListenerInterface;
use SaleBoss\Repositories\UserRepositoryInterface;

class LeadController extends BaseController
    implements CreatorListenerInterface, PickerListenerInterface {

    protected $auth;
    protected $creator;
    protected $delegateMan;
    protected $leadRepo;
    protected $userRepo;

    /**
     * @param AuthenticatorInterface                                  $auth
     * @param \SaleBoss\Services\Leads\Creator\CreatorInterface       $creator
     * @param \SaleBoss\Services\Leads\Presenter\DelegateManInterface $delegateMan
     * @param \SaleBoss\Repositories\LeadRepositoryInterface          $leadRepo
     * @param \SaleBoss\Repositories\UserRepositoryInterface          $userRepo
     */
    public function __construct(
        AuthenticatorInterface $auth,
        CreatorInterface $creator,
        DelegateManInterface $delegateMan,
        LeadRepositoryInterface $leadRepo,
        UserRepositoryInterface $userRepo
    ){
        $this->auth = $auth;
        $this->creator = $creator;
        $this->delegateMan = $delegateMan;
        $this->leadRepo = $leadRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Creation form for leads
     *
     * @return \View
     */
    public function create()
    {
        $title = $this->getTitle();
        $description = $this->getDescription();

        return $this->view(
           'admin.pages.lead.create',
           compact('title','description','currentUser')
        );
    }

    /**
     * Store leads in DB
     *
     * @return mixed
     */
    public function store()
    {
        $currentUser = $this->auth->user();
        $input = Input::get('leads');
        $this->creator->setListener($this);
        $this->creator->setCreator($currentUser);
        return $this->creator->bulkCreate($input);
    }

    /**
     * What to when bulk creation fails
     *
     * @param $messages
     * @return \Redirect
     */
    public function onCreateFail($messages)
    {
        return $this->redirectBack()->withErrors($messages);
    }

    /**
     * What do when bulk creation fails
     *
     * @param $message
     * @return \Redirect
     */
    public function onCreateSuccess($message)
    {
        return $this->redirectTo('leads/create')->with('success_message',$message);
    }

    public function index()
    {
        $title = $this->getTitle();
        $description = $this->getDescription();
        $currentUser = $this->auth->user();
        $this->delegateMan->setCurrentUser($currentUser);
        $list = $this->delegateMan->listIt();

        $statistics = $this->delegateMan->userStatistics();

        return $this->view(
            'admin.pages.lead.index',
            compact('title','description','list','statistics','currentUser','dataDash')
        );
    }

    public function leadPicker($id)
    {
        try {
            $this->delegateMan->setCurrentUser($this->auth->user());
            return $this->delegateMan->pick($id,$this);
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function lockerUpdate($id)
    {
        try {
            $input = Input::only("status","description");
            $lead = $this->leadRepo->findById($id);
            $lead->description = $input['description'];
            $lead->status = (int) $input['status'];
            $this->leadRepo->update($lead);
            return $this->redirectBack()->with('success_message',Lang::get("messages.operation_success"));
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }


    public function lockerEdit($id)
    {
        try {
            $currentUser = $this->auth->user();
            $lead = $this->leadRepo->findById($id);
            if ($currentUser->id != $lead->locker_id)
            {
                return $this->redirectBack()->with('error_message',Lang::get("messages.locker_not_allowed"));
            }
            $title = "بروز رسانی لید";
            $description = "فقط شما میتوانید این لید را ویرایش کنید.";
            return $this->view(
                'admin.pages.lead.locker_edit',
                compact('currentUser', 'lead', 'title', 'description')
            );
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function lockerRelease($id)
    {
        try {
            $currentUser = $this->auth->user();
            $lead = $this->leadRepo->findById($id);
            if ($lead->locker_id != $currentUser->id)
            {
                return $this->redirectBack()->with('error_message',Lang::get("messages.locker_not_allowed"));
            }
            if ($lead->status == 0)
            {
                return $this->redirectBack()->with('error_message',Lang::get("messages.release_not_allowed"));
            }
            $lead->locker_id = null;
            $lead->locked_at = null;
            $this->leadRepo->update($lead);
            return $this->redirectTo('leads')->with('success_message',Lang::get("messages.operation_success"));
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function edit($id)
    {
        try {
            $lead = $this->leadRepo->findById($id);
            $currentUser = $this->auth->user();
            if($currentUser->id != $lead->creator_id && ! $currentUser->hasAnyAccess(['leads.edit']))
            {
                return $this->redirectTo('dash')->with('error_message',Lang::get("messages.operation_error"));
            }
            return $this->view(
                "admin.pages.lead.edit",
                compact('title','lead')
            );
        }catch (NotFoundException $e){
            App::abort(404);
        }
    }

    public function onPickSuccess ($message)
    {
        return $this->redirectTo('leads?my_leads=1')->with('success_message',$message);
    }

    public function onPickFail ($messages)
    {
        return $this->redirectTo('leads?my_leads=1')->withErrors($messages);
    }

    public function usersLeads($id)
    {
        if (!$this->auth->user()->hasAnyAccess(['leads.user']))
        {
            return $this->redirectTo('dash')->with('error_message','شما اجازه مشاهده لیدهای کاربران دیگر را ندارید.');
        }

        $user = $this->userRepo->findById($id);
        $leads = $this->execute(ListCommand::class, compact('user'));
        $userCountAll = $this->leadRepo->getUserAllLeads($user);
        $userAllLeadsApproved = $this->leadRepo->getUserAllLeadsApproved($user);
        return $this->view('admin.pages.lead.user', compact('user', 'leads','userCountAll','userAllLeadsApproved'));
    }

    public function userLeadsWithTime($id)
    {
        if (!$this->auth->user()->hasAnyAccess(['leads.user']))
        {
            return $this->redirectTo('dash')->with('error_message','شما اجازه مشاهده لیدهای کاربران دیگر را ندارید.');
        }

        $user = $this->userRepo->findById($id);

        $firstTime = Input::get('from');
        $secondTime = Input::get('to');

        if (!empty($firstTime) && !empty($secondTime)) {
            $firstTimeEx = explode('-', $firstTime);
            $firstTimeG = Jalali\jDateTime::toGregorian($firstTimeEx[0], $firstTimeEx[1], $firstTimeEx[2]);
            $firstTime = $firstTimeG[0] . '-' . $firstTimeG[1] . '-' . $firstTimeG[2] . ' 00:00:00 00:00';

            $secondTimeEx = explode('-', $secondTime);
            $secondTimeG = Jalali\jDateTime::toGregorian($secondTimeEx[0], $secondTimeEx[1], $secondTimeEx[2]);
            $secondTime = $secondTimeG[0] . '-' . $secondTimeG[1] . '-' . $secondTimeG[2] . ' 00:00:00 00:00';
        }

        $leads = $this->execute(ListWithTimeCommand::class, compact('user','firstTime', 'secondTime'));
        return $this->view('admin.pages.lead.user_between', compact('user', 'leads'));
    }

    public function leadsAll()
    {
        if (!$this->auth->user()->hasAnyAccess(['leads.user']))
        {
            return $this->redirectTo('dash')->with('error_message','شما اجازه مشاهده لیدهای کاربران دیگر را ندارید.');
        }
        $leads = $this->leadRepo->getAllLeadPaginated();

        return $this->view('admin.pages.lead.all', compact('user', 'leads'));
    }

    public function notReads()
    {
        $user = $this->userRepo->findById($this->auth->user()->getId());

        $leads = $this->leadRepo->getRemindableLeads($user, 1);

        Event::fire('notifications.todayleads.read');

        return $this->view('admin.pages.lead.my_notread_list', compact('user', 'leads'));
    }
}