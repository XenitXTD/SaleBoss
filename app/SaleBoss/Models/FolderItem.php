<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\DB;

class FolderItem extends Eloquent {

    protected  $table = 'folders_items';

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

    public function file()
    {
        return $this->hasMany('SaleBoss\Models\Upload', 'for_id');
    }

    public function scopeGetFolderList($q, $forType, $forId, $count = null)
    {
        $root = array('ریشه');

        $q = DB::table($this->table)->where('for_type', $forType)->where('for_id', $forId)->orderBy('id','ASC');

        return $root + $q->lists('name', 'id');
    }
}