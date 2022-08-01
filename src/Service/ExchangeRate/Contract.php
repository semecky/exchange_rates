<?php
namespace App\Service\ExchangeRate;
interface Contract{
    public function get(\DateTime $date);
}