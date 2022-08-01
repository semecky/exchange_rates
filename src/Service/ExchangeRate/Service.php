<?php

namespace App\Service\ExchangeRate;

class Service implements Contract
{
    private GateContract $gate;

    public function __construct(GateContract $gate)
    {
        $this->gate = $gate;
    }

    public function get(\DateTime $date)
    {
        return $this->gate->get($date);
    }

    /**
     * @TODO implement database storage
     */
    protected function store()
    {

    }
    /**
     * @TODO implement database storage
     */
    protected function getStored()
    {

    }
}