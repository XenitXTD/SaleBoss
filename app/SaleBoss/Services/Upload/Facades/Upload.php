<?php namespace SaleBoss\Services\Upload\Facades;

use Illuminate\Support\Facades\Facade;

class Upload extends Facade {

    /**
     * Menu Builder IoC
     *
     * @return string
     */
    protected static function getFacadeAccessor(){return "Upload";}
}