<?php namespace SaleBoss\Services\Notification\Facades;

use Illuminate\Support\Facades\Facade;

class Notification extends Facade {
    /**
     * Menu Builder IoC
     *
     * @return string
     */
    protected static function getFacadeAccessor(){return "Notification";}
}