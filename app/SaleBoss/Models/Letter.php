<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Letter extends Eloquent {

    protected  $table = 'letters';

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

    public function folder()
    {
        return $this->belongsTo('SaleBoss\Models\Folder', 'folder_id');
    }

    public function path()
    {
        return $this->hasMany('SaleBoss\Models\LetterPath', 'letter_id');
    }

    public function destinationP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'destination');
    }

    public function startP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'start');
    }
}