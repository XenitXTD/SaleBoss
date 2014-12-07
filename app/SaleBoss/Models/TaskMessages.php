<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class TaskMessages extends Eloquent {

    protected  $table = 'tasks_messages';

    use DateTrait;

    protected $dates = ['created_at','updated_at'];

    public function creator()
    {
        return $this->belongsTo('SaleBoss\Models\User', 'creator_id');
    }

    public function forTask()
    {
        return $this->belongsTo('SaleBoss\Models\Task', 'task_id');
    }

}