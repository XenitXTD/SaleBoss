<?php
namespace SaleBoss\Services\ViewBuilder;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class CallBuilder {

    protected $builder;

    function __construct(
        Builder $builder
    )
    {
        $this->builder = $builder;
    }

    public function __call($name, $arguments)
    {
        $this->builder->resetOutput();
        if (!empty($arguments)){
            $nameSplit = preg_split('/(?<=[a-z])[A-Z]/', $name);
            $probClass = "\\SaleBoss\\Services\\ViewBuilder\\Builders\\" . ucfirst(Str::camel($nameSplit[0])) . "Builder";

            if(class_exists($probClass)){
                $custom = App::make($probClass);
                return call_user_func_array([$custom,$name], $arguments);
            }
        }
        return call_user_func_array([$this->builder,$name], $arguments);
    }

} 