<?php namespace Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\GroupRepositoryInterface;
use SaleBoss\Services\Validator\GroupValidator;

class GroupController extends BaseController
{

	protected $groupRepo;
	protected $groupValidator;

	/**
	 * @param GroupRepositoryInterface $groupRepo
	 * @param GroupValidator $groupValidator
	 */
	public function __construct(
		GroupRepositoryInterface $groupRepo,
		GroupValidator $groupValidator
	)
	{
		$this->groupRepo = $groupRepo;
		$this->groupValidator = $groupValidator;
        $this->beforeFilter('hasPermission:groups');
	}

	/**
	 * Creation form for user groups
	 *
	 * @return View
	 */
	public function create()
	{
		$groups = $this->groupRepo->getAll();
		return $this->view('admin.pages.group.create', compact('groups'));
	}

	/**
	 * Store a
	 *
	 * @return Redirect
	 */
	public function store()
	{
		$input = Input::get('item');
		if (!$valid = $this->groupValidator->isValid($input)) {
			return $this->redirectBack()->withErrors($this->groupValidator->getMessages())->withInput();
		}
		$this->groupRepo->create($input);
		return $this->redirectTo('groups')->with('success_message', Lang::get('operation_success'));
	}

	/**
	 * Update a grooup in repo
	 *
	 * @param $id
	 * @return Redirect
	 */
	public function update($id)
	{
		$input = Input::get('item');
		$this->groupValidator->setCurrentIdFor('name',$id);
		if (!$valid = $this->groupValidator->isValid($input)) {
			return $this->redirectBack()->withInput()->withErrors($this->groupValidator->getMessages());
		}

		try {
			$this->groupRepo->update($id,$input);
			return $this->redirectBack()->with('success_message', Lang::get('messages.operation_success'));
		} catch (NotFoundException $e) {
			App::abort(404);
		}
	}

	/**
	 * Delete an item from repo when id is provided
	 *
	 * @param $id
	 */
	public function destroy($id)
	{
		try {
			$this->groupRepo->delete($id);
			return $this->redirectTo('groups')->with('success_message', Lang::get('operation_success'));
		} catch (NotFoundException $e) {
			App::abort(404);
		}
	}

	/**
	 * List groups
	 *
	 * @return List groups
	 */
	public function index()
	{
		$groups = $this->groupRepo->getAll();
		return $this->view('admin.pages.group.index')->with('groups',$groups);
	}

	/**
	 * @param $id
	 * @return View
	 */
	public function edit($id)
	{
		try {
			$update = true;
			$groups = $this->groupRepo->getAll();
			$group = $this->groupRepo->findById($id);
			return $this->view(
				'admin.pages.group.edit',
				compact('update','group', 'groups')
			);
		}catch (NotFoundException $e){
			App::abort(404);
		}
	}

} 