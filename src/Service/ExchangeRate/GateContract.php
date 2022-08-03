<?php
namespace App\Service\ExchangeRate;
interface GateContract{
    /**
     * @param \DateTime $date
     * @return array
     * @throws GateException
     */
    public function get(\DateTime $date):array;

}