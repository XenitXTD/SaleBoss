<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Upload extends Eloquent {

    protected  $table = 'upload';

    public function forWhom()
    {
        return $this->belongsTo('SaleBoss\Models\User', 'creator_id');
    }

}