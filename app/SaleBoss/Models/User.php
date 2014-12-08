<?php

namespace SaleBoss\Models;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Cartalyst\Sentry\Users\Eloquent\User as SentryUser;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class User extends SentryUser {

	use DateTrait;
	use ChartTrait;
    use SoftDeletingTrait;

    protected  $table = 'users';

    /**
     * Get user idetifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        if (!empty($this->first_name)) return "{$this->first_name} {$this->last_name}";
        if (!empty($this->email)) return $this->email;
        return $this->id;
    }

	/**
	 * Self relationship
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function generatedUsers()
	{
		return $this->hasMany('SaleBoss\Models\User','creator_id');
	}

	/**
	 * Get a combination of first name and last name
	 *
	 * @return string
	 */
	public function name()
	{
		return "{$this->first_name} {$this->last_name}";
	}

    /**
     * User Custom avatar generator
     *
     * @return int
     */
    public function avatar()
    {
        return 'files/avatars/' . (($this->id % 10) + 1) . '.gif';
    }

    /**
     * Self relationship between user and it's creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function creator()
    {
        return $this->belongsTo('SaleBoss\Models\User','creator_id');
    }

    /**
     * Accessor for getting connection_way from config
     *
     * @return string
     */
    public function getConnectionWayAttribute($value)
    {
        $config = Config::get('connection_types');
        if (isset($config[$value]))
        {
            return $config[$value];
        }else
        {
            return  $value;
        }
    }

	/**
	 * User Badges
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function badges()
	{
		return $this->hasMany('SaleBoss\Models\UserBadge','user_id');
	}

	/**
	 * User Rates, each rate contains a date period with this format [x,null) or [x,y]
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function rates()
	{
		return $this->hasMany('SaleBoss\Models\UserRate','user_id');
	}

	/**
	 * User Change logs on orders
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function changerLogs()
	{
		return $this->hasMany('SaleBoss\Models\OrderLog','changer_id');
	}

	/**
	 * Previous user change logs
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function previousChangerLogs()
	{
		return $this->hasMany('SaleBoss\Models\OrderLog','previous_changer_id');
	}

    /**
     * User created orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('SaleBoss\Models\Order','creator_id');
    }

    /**
     * User created leads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdLeads()
    {
        return $this->hasMany('SaleBoss\Models\Lead','creator_id');
    }

    /**
     * User locked leads
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lockedLeads(){
        return $this->hasMany('SaleBoss\Models\Lead','locker_id');
    }

    public function scopeMakeSortable($q)
    {
        $sortBy = Input::get('sort_by');
        $asc = Input::get('asc');
        if (in_array($sortBy, ['created_at', 'updated_at', 'id'])) {
            $q->orderBy($sortBy, empty($asc) ? "DESC" : "ASC");
        } else {
            $q->orderBy('created_at', 'DESC');
        }
    }

    public function scopeGetUserList($q, $count = null)
    {
        $q = DB::table($this->table)->where('is_customer', false)->orderBy('id','ASC')->select(DB::raw('concat (first_name," ",last_name) as full_name,id'));
        if (is_null($count)) {
                return $q->lists('full_name', 'id');
        }else {
            return $q->take($count)->lists('first_name, last_name','id');
        }
    }

    public function scopeGetUserListByName($q, $name, $count = null)
    {
        $group = Sentry::findGroupByName($name);

        $users = Sentry::findAllUsersInGroup($group);

        return $users->lists('last_name','id');
    }
}