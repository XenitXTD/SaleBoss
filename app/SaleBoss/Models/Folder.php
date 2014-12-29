<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class Folder extends Eloquent {

    public  $table = 'folders';

    use DateTrait;

    protected $dates = ['created_at','updated_at'];

	/**
     * Create Relation between task and it's creator
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('SaleBoss\Models\User', 'creator_id');
    }

    public function parent()
    {
        return $this->belongsTo('SaleBoss\Models\Folder', 'parent_id');
    }
}