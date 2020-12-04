<?php

namespace App\CurrencyProvider;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeratesapiProvider implements ProviderInterface
{
    private const API_URL = 'https://api.exchangeratesapi.io/latest?base=PLN';

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public static function getDefaultPriority(): int
    {
        return 1;
    }

    public function getSource(): string
    {
        return self::API_URL;
    }

    /**
     * @return array<CurrencyModel>
     *
     * @throws \Exception
     */
    public function fetch(): array
    {
        $out = [];
        $response = $this->getFromRemote();
        $items = $response['rates'];

        foreach ($items as $code => $rate) {
            $currency = new CurrencyModel();
            $currency
                ->setCode($code)
                ->setRate((float) $rate)
            ;
            $out[] = $currency;
        }

        return $out;
    }

    private function getFromRemote(): array
    {
        $response = $this->client->request('GET', self::API_URL);

        try {
            return json_decode($response->getContent(), true);
        } catch (\Exception $ex) {
            throw new \Exception($response->getContent(false));
        }
    }
}
