<?php namespace SaleBoss\Services\ViewBuilder\Facades;

use Illuminate\Support\Facades\Facade;

class ViewBuilder extends Facade {
    /**
     * Menu Builder IoC
     *
     * @return string
     */
    protected static function getFacadeAccessor(){return "ViewBuilder";}
}