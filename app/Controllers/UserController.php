<?php namespace Controllers;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\GroupRepositoryInterface;
use SaleBoss\Repositories\UserRepositoryInterface;
use SaleBoss\Services\User\Creator;
use SaleBoss\Services\User\CreatorListenerInterface;
use SaleBoss\Services\User\Dashboard;
use SaleBoss\Services\User\UpdateListenerInterface;

class UserController extends BaseController
	implements
		CreatorListenerInterface,
		UpdateListenerInterface
{

	protected $userRepo;
	protected $groupRepo;
	protected $creator;
	protected $dash;

	/**
	 * @param UserRepositoryInterface $userRepo
	 * @param GroupRepositoryInterface $groupRepo
	 * @param Creator $creator
	 * @param Dashboard $dashboard
	 */
	public function __construct(
		UserRepositoryInterface $userRepo,
		GroupRepositoryInterface $groupRepo,
		Creator $creator,
		Dashboard $dashboard
	)
	{
		$this->userRepo = $userRepo;
		$this->groupRepo = $groupRepo;
		$this->creator = $creator;
		$this->dash = $dashboard;
		$this->beforeFilter('hasPermission:user.create',['only' => 'create']);
		$this->beforeFilter('hasPermission:user.delete',['only' => 'delete']);
		// $this->beforeFilter('hasPermission:user.edit',['only' => 'edit']);
		$this->beforeFilter('hasPermission:user.view',['only' => 'index']);
	}

	/**
	 * User Dashboard page
	 *
	 * @return View
	 */
	public function getDash()
	{
		$this->dash->setUser(Sentry::getUser());
		$data = $this->dash->getHisDash();
        $data['inDashboard'] = true;
		JavaScript::put(['orderChart' => $data['orderChart'] , 'myLeadStats' => $data['myLeadStats']]);
		return $this->view(
				'admin.pages.dashboard.main', $data
			);
	}

	/**
	 * List of users
	 *
	 * @return View
	 */
	public function index()
	{
		$dynamicItems = $this->userRepo->getAllPaginated(50,true);
		$operationColumn = 'admin.pages.user.partials._operation';
		$columns = Config::get('dynamic_data/datatables.users');
		return $this->view(
			$this->getView(
				'admin.pages.user.index',
				'admin.pages.common.index'
			),
			compact(
				'dynamicItems',
				'operationColumn',
				'columns'
			)
		);
	}

	/**
	 * User Creation form
	 *
	 * @return \View
	 */
	public function create()
	{
		$groups = $this->groupRepo->getAll();
		$groups = $groups->lists('display_name', 'id');
		return $this->view('admin.pages.user.create', compact('groups'));
	}

	/**
	 * Edit Form for user
	 *
	 * @param $id
	 * @return View
	 */
	public function edit($id)
	{
		try {
            if ( ! $this->editCallback($id))
            {
                return $this->redirectTo('dash')->with('error_message','شما اجازه دسترسی به صفحه مورد نظر رد ندارید');
            }
			$groups = $this->groupRepo->getAll();
			$groups = $groups->lists('display_name', 'id');
			$user = $this->userRepo->findById($id);
			$current_groups = $this->groupRepo->getUserGroups($user);
			$current_groups = $current_groups->lists('id');
			$update = true;
			return $this->view('admin.pages.user.edit', compact('groups', 'user', 'update', 'current_groups'));
		} catch (NotFoundException $e) {
			App::abort(404);
		}
	}

	/**
	 * Store a user in db
	 *
	 * @return Redirect
	 */
	public function store()
	{
		$input = Input::get('item');
		return $this->creator->create($input, $this);
	}


	/**
	 * What to do when creation fails
	 *
	 * @param $messages
	 * @return Redirect
	 */
	public function onCreateFail($messages)
	{
		return $this->redirectBack()->withErrors($messages)->withInput();
	}

	/**
	 * What to do when user creation succeeds
	 *
	 * @param null $message
	 * @return Redirect
	 */
	public function onCreateSuccess($message = null)
	{
		return $this->redirectTo('users')->with(
			'success_message', !empty($message) ? $message : Lang::get('messages.user_created')
		);
	}

	/**
	 * Delete a user from repo
	 *
	 * @param $id
	 * @return Redirect
	 */
	public function destroy($id)
	{
        if(! Sentry::getUser()->hasAnyAccess(['users.delete']))
        {
            return $this->redirectTo('dash')->with('error_message',Lang::get("messages.permission_denied"));
        }
		try {
			$this->userRepo->delete($id);
			return $this->redirectBack()->with('success_message', Lang::get('messages.operation_success'));
		} catch (NotFoundException $e) {
			App::abort(404);
		}
	}

	/**
	 * Update a user in repo
	 *
	 * @param $id
	 * @return Redirect
	 */
	public function update($id)
	{
		return $this->creator->update($id, Input::get('item'), $this);
	}

	/**
	 * What to do when user update fails
	 *
	 * @param $messages
	 * @return Redirect
	 */
	public function onUpdateFail($messages)
	{
		return $this->redirectBack()->withInput()->withErrors($messages);
	}

	/**
	 * What to do when user update succeeds
	 *
	 * @param null $message
	 * @return Redirect
	 */
	public function onUpdateSuccess($message = null)
	{
		return $this->redirectBack()->with(
			'success_message',
			empty($message) ? Lang::get('messages.operation_success') : $message
		);
	}

	/**
	 * What to do when user is not found for update
	 *
	 * @return void
	 */
	public function onUpdateNotFound()
	{
		App::abort(404);
	}

	/**
	 * Show users
	 * @param $id
	 */
	public function show($id)
	{
        return $this->redirectTo('users/'. $id . '/edit');
	}

    /**
     * @param $id
     *
     * @return bool
     */
    protected function editCallback($id)
    {
        $current_id = Sentry::getUser()->id;
        $user_id = $id;
        if (Sentry::getUser()->hasAnyAccess(['users.edit']))
        {
            return true;
        }
        if ($user_id != $current_id)
        {
            return false;
        }else {
            return true;
        }
    }

    public function profileEdit()
    {
        $user = Sentry::getUser();
        $title = $this->getTitle();
        $description = $this->getDescription();
        return $this->view(
            'admin.pages.user.profile_edit',
            compact('user','title','description')
        );
    }

    public function profileUpdate()
    {
        return $this->creator->updateMe(Sentry::getUser(), Input::get("item"), $this);
    }
}