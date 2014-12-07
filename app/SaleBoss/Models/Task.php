<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Miladr\Jalali\jDate;

class Task extends Eloquent {

    protected  $table = 'tasks';

    use DateTrait;

    protected $dates = ['created_at','updated_at','todo_at'];

	/**
     * Create Relation between task and it's creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('SaleBoss\Models\User', 'creator_id');
    }

	/**
     * Create Relation between task and it's customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forWhom()
    {
        return $this->belongsTo('SaleBoss\Models\User', 'for_id');
    }

	/**
     * Create Relation between task and it's category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cat()
    {
        return $this->belongsTo('SaleBoss\Models\TaskCategory', 'category');
    }

	/**
     * Create Relation between task and it's messgaes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function message()
    {
        return $this->hasMany('SaleBoss\Models\TaskMessages', 'task_id');
    }

    public function file()
    {
        return $this->hasMany('SaleBoss\Models\Upload', 'for_id');
    }

	/**
     * Generate Time for created at task
     *
     * @return mixed
     */
    public function diff_create_at()
    {
        return jDate::forge($this->created_at)->ago();
    }
}