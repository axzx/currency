<?php

namespace App\Manager;

use App\CurrencyProvider\CurrencyContext;
use App\CurrencyProvider\CurrencyModel;
use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyUpdateManager
{
    private $em;
    private $currencyContext;

    private $currencies = [];

    public function __construct(EntityManagerInterface $em, CurrencyContext $currencyContext)
    {
        $this->em = $em;
        $this->currencyContext = $currencyContext;
    }

    public function update(): string
    {
        $this->preload();

        $items = $this->currencyContext->handleFetch();

        foreach ($items as $item) {
            if (!($item instanceof CurrencyModel)) {
                continue;
            }

            if (!isset($this->currencies[$item->getCode()])) {
                $currency = new Currency();
                $this->em->persist($currency);
            } else {
                $currency = $this->currencies[$item->getCode()];
            }

            $this->updateOne($currency, $item);
        }
        $this->em->flush();

        return (string) $this->currencyContext->getSource();
    }

    private function preload(): void
    {
        $result = $this->em->getRepository(Currency::class)
            ->findAll()
        ;

        foreach ($result as $currency) {
            $this->currencies[$currency->getCode()] = $currency;
        }
    }

    private function updateOne(Currency $currency, CurrencyModel $currencyModel): void
    {
        $oldRate = $currency->getRate();

        if ($currencyModel->getName()) {
            $currency->setName($currencyModel->getName());
        }

        $currency
            ->setCode($currencyModel->getCode())
            ->setRate($currencyModel->getRate())
        ;

        if ($oldRate !== $currency->getRate()) {
            $currency->updateRateChangeAt();
        }
    }
}
