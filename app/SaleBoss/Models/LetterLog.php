<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LetterLog extends Eloquent {

    protected  $table = 'letters_log';

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

    public function forPath()
    {
        return $this->belongsTo('SaleBoss\Models\LetterPath', 'letter_path');
    }

}