<?php namespace SaleBoss\Repositories\Eloquent;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use SaleBoss\Models\Group;
use SaleBoss\Models\User;
use SaleBoss\Repositories\Collection;
use SaleBoss\Repositories\Exceptions\NotFoundException;
use SaleBoss\Repositories\GroupRepositoryInterface;

class GroupRepository extends AbstractRepository implements GroupRepositoryInterface{

	protected  $model;

	/**
	 * @param Group $model
	 */
	public function __construct(
		Group $model
	){
		$this->model = $model;
	}

	/**
	 * Get Groups of current user
	 *
	 * @param $user
	 * @return mixed
	 */
	public function getUserGroups(User $user)
	{
		return $user->getGroups();
	}

    /**
     * Add groups to user
     *
     * @param User   $user
     * @param array  $groups
     * @param string $key
     * @return \SaleBoss\Models\User
     */
	public function addGroupsToUser(User $user, $groups, $key = 'id')
	{
        $groups = (array) $groups;
        $groups = empty($groups) ? [time().uniqid()] : $groups;
		$groups = $this->model->whereIn($key,$groups)->get();
		if ($groups->isEmpty()){
			return $user;
		}

		foreach($groups as $group){
			$user->addGroup($group);
		}
		return $user;
	}

	/**
	 * Create a group in Repo
	 *
	 * @param array $data
	 * @return \SaleBoss\Models\Group
	 */
	public function create(array $data)
	{
		$model = $this->model->newInstance();
		$model->name = $data['name'];
		$model->display_name = $data['display_name'];
		$model->parent_id = ($data['parent_id'] == 0) ? Null : $data['parent_id'];
		return $model->save();
	}

	/**
	 * Update an existing repository in db when id
	 * is provided
	 *
	 * @param $id
	 * @param $data
	 * @return bool
	 * @throws \SaleBoss\Repositories\Exceptions\NotFoundException
	 */
	public function update($id , $data)
	{
		$model = $this->model->newInstance()->find($id);
		if(is_null($model)){
			throw new NotFoundException("No Group with id: [{$id}] found");
		}
		$model->name = $data['name'];
		$model->display_name = $data['display_name'];
		$model->parent_id = ($data['parent_id'] == 0) ? Null : $data['parent_id'];
		return $model->save();
	}

    /**
     * Add Permissions to group
     *
     * @param \SaleBoss\Models\Group    $group
     * @param array                     $permissions
     *
     * @return \SaleBoss\Models\Group
     */
    public function addPermissions(Group $group, array $permissions)
    {
	    $group->setPermissionsAttribute($permissions,false);
        $group->save();
        return $group;
    }

    /**
     * Get all permissions where id is in array:
     *
     * @param $in
     * @return \SaleBoss\Models\Group;
     */
    public function getAllWhereId($in)
    {
        return $this->model->newInstance()->whereIn('id',$in)->get();
    }

	/**
	 * Remove all user groups
	 *
	 * @param $user
	 * @return void
	 */
	public function removeUserGroups(User $user)
	{
		$groups = $user->getGroups();
		foreach($groups as $group)
		{
			$user->removeGroup($group);
		}
	}
}