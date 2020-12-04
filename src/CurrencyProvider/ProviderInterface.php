<?php

namespace App\CurrencyProvider;

interface ProviderInterface
{
    public function getSource(): string;

    /**
     * @return array<CurrencyModel>
     */
    public function fetch(): array;
}
