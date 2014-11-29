<?php namespace Controllers\Opilo;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Controllers\BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use SaleBoss\Models\User;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\Authenticator\AuthenticatorInterface;
use SaleBoss\Services\User\CustomerCreator;
use SaleBoss\Services\User\CustomerCreatorListenerInterface;
use SaleBoss\Services\User\CustomerUpdaterListenerInterface;

class CustomerController extends BaseController
    implements
        CustomerCreatorListenerInterface,
        CustomerUpdaterListenerInterface
        {

    protected $userRepo;
    protected $creator;
    protected $auth;

    public function __construct(
        CustomerCreator $creator,
        UserRepositoryInterface $userRepo,
        AuthenticatorInterface $auth
    ){
        $this->creator = $creator;
        $this->userRepo = $userRepo;
        $this->auth = $auth;
        $this->beforeFilter('hasPermission:customers.own_edit',['only' => ['edit','update']]);
    }

    /**
     * Customer creation form
     *
     * @return View
     */
    public function create()
    {
        $title = 'ایجاد مشتری جدید';
        $description = 'مشتری که در این قسمت ایجاد میکنید در سیستم به نام شما ثبت خواهد شد.';
        return $this->view(
            'admin.pages.customer.create',
            compact('title','description')
        );
    }

    /**
     * Store a customer in database
     *
     * @return Redirect
     */
    public function store()
    {
        $customer = Input::get('user');
        $this->creator->setListener($this);
        return $this->creator->create($customer);
    }

    /**
     * What to do when storing in db is successfull
     *
     * @param null $message
     *
     * @return Redirect
     */
    public function onStoreSuccess($message = null, User $user)
    {
        $message = is_null($message) ? Lang::get('messages.operation_success') : $message;
        if (! Input::has('to_orders'))
        {
            return $this->redirectBack()->with('success_message',$message);
        }else {
            return $this->redirectTo('orders')->with('success_message',$message);
        }
    }

    /**
     * What to do when storing in db is not successful
     *
     * @param $messages
     *
     * @return Redirect
     */
    public function onStoreFail($messages)
    {
        return $this->redirectBack()->withErrors($messages)->withInput();
    }

    /**
     * Listing Users Customers
     *
     * @return View
     */
    public function myIndex()
    {
        $searches = Input::only(
            "first_name",
            "last_name",
            "mobile",
            "tell",
            "description",
            "email",
            "id",
            "creator_id"
        );
        $title = 'لیست مشتریان من';
        $description = 'لیست مشتریانی که من ایجاد کرده ام';
        $myCustomers = $this->userRepo->getCustomers(Sentry::getUser(),50,$searches);
        return $this->view(
            'admin.pages.customer.my_index',
            compact('myCustomers','title','description')
        );
    }

    /**
     * Showing a customer
     *
     * @param $id
     * @return View
     */
    public function show($id)
    {
        try
        {
            $customer = $this->userRepo->findCustomer($id);
            $title = "مشاهده مشتری {$customer->getIdentifier()}";
            $description = "این کاربر توسط {$customer->creator()->first()->getIdentifier()} ایجاد شده است. ";
            return $this->view(
                'admin.pages.customer.show',
                compact('customer','title','description')
            );
        }catch (NotFoundException $e)
        {
            App::abort(404);
        }
    }

    /**
     * List all customers
     *
     * @return View
     */
    public function index()
    {
	    $title = 'لیست همه مشتریان';
	    $description = 'لیست همه مشتریانی که در سیستم هستند';
	    $searches = Input::only(
		    "first_name",
		    "last_name",
		    "mobile",
		    "tell",
		    "description",
		    "email",
		    "id",
            "creator_id"
	    );
        if ( ! $this->auth->user()->hasAnyAccess(['customers.view_all']))
        {
            return $this->redirectTo('my/customers');
        }
	    $myCustomers = $this->userRepo->getCustomers(null,50,$searches);
        return $this->view(
	        'admin.pages.customer.index',
			compact('title','description','myCustomers')
        );
    }

    /**
     * Customer edit form
     *
     * @param $id
     * @return View
     */
    public function edit($id)
    {
        try
        {
            $customer = $this->userRepo->findCustomer($id);
            if ($this->auth->user()->id != $customer->creator_id && !$this->auth->user()->hasAnyAccess(['customers.edit']))
            {
                return $this->redirectTo('dash')->with('error_message','شما اجازه ویرایش کاربری که ایجاد نکرده اید را ندارید.');
            }
            $title = "ویرایش مشتری {{$customer->name()}}";
            $description = "این مشتری توسط {$customer->creator()->first()->getIdentifier()} ایجاد شده است";
            $update = true;
            return $this->view(
                'admin.pages.customer.edit',
                compact('customer','update','title','description')
            );
        }catch (NotFoundException $e )
        {
            App::abort(404);
        }
    }

    /**
     * Perform customer update
     *
     * @param $id
     * @return Redirect
     */
    public function update($id)
    {
        try
        {
            $input = Input::get('user');
            $this->creator->setUpdateListener($this);
            $customer = $this->userRepo->findCustomer($id);
            if ($this->auth->user()->id != $customer->creator_id && !$this->auth->user()->hasAnyAccess(['customers.edit']))
            {
                return $this->redirectTo('dash')->with('error_message','شما اجازه ویرایش کاربری که ایجاد نکرده اید را ندارید.');
            }
            return $this->creator->update($customer,$input);
        }catch (NotFoundException $e)
        {
            App::abort(404);
        }
    }

    /**
     * What to do when update fails
     *
     * @param $messages
     *
     * @return mixed
     */
    public function onUpdateFail($messages)
    {
        return $this->redirectBack()->withInput()->withErrors($messages);
    }

    /**
     * What to do when update succeeds
     *
     * @param $message
     *
     * @return mixed
     */
    public function onUpdateSuccess($message = [])
    {
        return $this->redirectBack()->with('success_message',Lang::get('messages.operation_success'));
    }
}