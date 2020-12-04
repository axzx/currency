<?php

namespace App\CurrencyProvider;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NbpProvider implements ProviderInterface
{
    private const API_URL = 'http://api.nbp.pl/api/exchangerates/tables/A';

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public static function getDefaultPriority(): int
    {
        return 1000;
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
        $pa = PropertyAccess::createPropertyAccessor();
        $out = [];
        $response = $this->getFromRemote();
        $items = $response[0]['rates'];

        foreach ($items as $item) {
            $currency = new CurrencyModel();
            $currency
                ->setName($pa->getValue($item, '[currency]'))
                ->setCode($pa->getValue($item, '[code]'))
                ->setRate((float) $pa->getValue($item, '[mid]'))
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
