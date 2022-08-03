<?php

namespace App\Service\ExchangeRate;

use App\Entity\Currency;
use App\Entity\ExchangeRate;
use App\Repository\CurrencyRepository;
use App\Repository\ExchangeRateRepository;
use Doctrine\Persistence\ManagerRegistry;

class Service implements Contract
{
    private GateContract $gate;
    private CurrencyRepository $currencyRepository;
    private ExchangeRateRepository $exchangeRateRepository;
    private ManagerRegistry $doctrine;

    public function __construct(
        GateContract $gate,
        CurrencyRepository $currencyRepository,
        ExchangeRateRepository $exchangeRateRepository,
        ManagerRegistry $doctrine
    )
    {
        $this->gate = $gate;
        $this->currencyRepository = $currencyRepository;
        $this->exchangeRateRepository = $exchangeRateRepository;
        $this->doctrine = $doctrine;
    }

    /**
     * @param \DateTime $date
     * @return ExchangeRate[]
     */
    public function get(\DateTime $date): array
    {
        $rates = $this->getStored($date);
        if ( count($rates) > 0 )
            return $rates;
        /**
         * @TODO Тут наверное лучше получать DTO или сразу Entity
         */
        try {
            $rawRates = $this->gate->get($date);
            return $this->store($date, $rawRates);
        } catch (GateException $e){
            /**
             * @TODO Тут конечно надо залоггировать
             */
            return [];
        }

    }

    protected function store(\DateTime $date, array $rawRates) : array
    {
        /*
         * Me is Karen, and me wants to see a manager now!
         */
        $entityManager = $this->doctrine->getManager();
        $currencies = $this->currencyRepository->findAll();
        $rates = [];
        foreach ($rawRates as $rawRate){
            /*
             * @TODO сделать нормальные коллекции
             */
            $foundCurrency = array_filter(
                $currencies,
                fn(Currency $currency) => $currency->getOriginId() === $rawRate['origin_id']
            );
            if( count($foundCurrency) == 0){
                $currency = new Currency();
                $currency->setOriginId($rawRate['origin_id']);
                $currency->setOriginCode($rawRate['origin_code']);
                $currency->setOriginNumber($rawRate['origin_number']);
                $currency->setName($rawRate['name']);
                $currencies[] = $currency;
                $entityManager->persist($currency);
            }else{
                $currency = current($foundCurrency);
            }
            $rate = new ExchangeRate();
            $rate->setValue($rawRate['rate']);
            $rate->setDate($date);
            $rate->setCurrency($currency);
            $rates[] = $rate;
            $entityManager->persist($rate);
        }
        $entityManager->flush();
        return  $rates;
    }
    protected function getStored(\DateTime $date): array
    {
        return $this->exchangeRateRepository->findBy(['date'=>$date]);
    }
}