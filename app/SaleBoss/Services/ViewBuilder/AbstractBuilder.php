<?php
namespace SaleBoss\Services\ViewBuilder;

abstract class AbstractBuilder {

    protected $output;

    public function resetOutput()
    {
        $this->output = null;

        return $this;
    }
} 