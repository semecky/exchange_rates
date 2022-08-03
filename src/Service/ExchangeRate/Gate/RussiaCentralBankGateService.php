<?php

namespace App\Service\ExchangeRate\Gate;

use App\Service\ExchangeRate\GateContract;
use App\Service\ExchangeRate\GateException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Stream;
use Psr\Http\Message\StreamInterface as StreamInterfaceAlias;
use Symfony\Component\DomCrawler\Crawler;

enum Uris : string
{
    case Daily = 'scripts/XML_daily.asp';
}

class RussiaCentralBankGateService implements GateContract
{
    protected Client $client;
    protected Crawler $crawler;

    protected string $baseUrl = 'http://www.cbr.ru/';

    public function __construct()
    {
        $this->client = new Client();
        $this->crawler = new Crawler();
    }

    public function get(\DateTime $date):array
    {
        try {
            $data = $this->request($date);
            return $this->format($data);
        } catch (GuzzleException $e){
            throw new GateException('Gate error: '.$e->getMessage());
        }
    }

    /**
     * @param \DateTime $data
     * @throws GuzzleException
     */
    private function request(\DateTime $date): string
    {
        return ($this->client->request(
            'GET',
            $this->baseUrl.Uris::Daily->value,
            [
                'query'=>['date_req'=>$date->format('d/m/Y')],
                'headers'=>['User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36',]
            ],
        ))->getBody()->getContents();
    }
    protected function format(string $data)
    {
        $this->crawler->addContent($data);
        return $this->crawler->filter('Valute')->each(function (Crawler $node, $i) {
            return [
                'origin_id'     => $node->attr('ID'),
                'origin_number' => $node->filter('NumCode')->text(),
                'origin_code'   => $node->filter('CharCode')->text(),
                'name'          => $node->filter('Name')->text(),
                'rate'          => str_replace(',', '.', $node->filter('Value')->text()),
            ];
        });
    }
}