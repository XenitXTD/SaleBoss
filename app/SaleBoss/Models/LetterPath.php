<?php namespace SaleBoss\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LetterPath extends Eloquent {

    public  $table = 'letters_path';

    use DateTrait;

    protected $dates = ['created_at','updated_at'];


    public function startP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'start');
    }

    public function destinationP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'destination');
    }

    public function currentP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'current_place');
    }

    public function prevP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'prev_place');
    }

    public function nextP()
    {
        return $this->belongsTo('SaleBoss\Models\Group', 'next_place');
    }

    public function letter()
    {
        return $this->belongsTo('SaleBoss\Models\Letter', 'letter_id');
    }

    public function log()
    {
        return $this->hasMany('SaleBoss\Models\LetterLog', 'letter_path');
    }
}