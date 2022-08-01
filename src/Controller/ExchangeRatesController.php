<?php

namespace App\Controller;

use App\Service\ExchangeRate\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class ExchangeRatesController extends AbstractController
{
    private Service $exchangeService;

    public function __construct(Service $exchangeService)
    {
        $this->exchangeService = $exchangeService;
    }

    #[Route('/exchange_rates', name: 'app_exchange_rates', methods: ['GET'])]
    #[Route('/', name: 'homepage')]
    /**
     * Returns main page of the exchange rates
     */
    public function index(): Response
    {
        return $this->render('exchange_rate/index.html.twig');
    }

    #[Route(
        '/exchange_rates/{date}',
        name: 'app_exchange_rate',
        requirements: ['date'=>'(?:0[1-9]|[12][0-9]|(?<!02-)3[01]).(?:0[1-9]|1[012]).[0-9]{4}'], //DD.MM.YYYY string validation
        methods: ['GET']
    )]
    public function getExchangeRate(\DateTime $date): Response
    {
        $data = $this->exchangeService->get($date);
        $responseString = '';
        foreach ($data as $item){
            $responseString .= $this->renderView('components/currency_rate.html.twig', ['currentRate'=>$item]);
        }
        return new Response($responseString);
    }
}
