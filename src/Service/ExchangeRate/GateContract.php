<?php
namespace App\Service\ExchangeRate;
interface GateContract{
    public function get(\DateTime $date);

}