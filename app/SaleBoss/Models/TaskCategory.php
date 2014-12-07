<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class TaskCategory extends Eloquent {

    protected  $table = 'tasks_categories';

    public function scopeGetCategoryList($query, $count = null)
    {
        $query = $query->orderBy('id','ASC');
        if (is_null($count)) {
            return $query->get()->lists('name','id');
        }else {
            return $query->take($count)->get()->lists('name', 'id');
        }
    }

}