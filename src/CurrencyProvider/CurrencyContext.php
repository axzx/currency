<?php

namespace App\CurrencyProvider;

class CurrencyContext
{
    /**
     * @var array|ProviderInterface[]
     */
    private $providers = [];

    private $source;

    public function addProvider(ProviderInterface $provider): self
    {
        $this->providers[] = $provider;

        return $this;
    }

    /**
     * @return array<CurrencyModel>
     */
    public function handleFetch(): array
    {
        foreach ($this->providers as $provider) {
            try {
                $this->source = $provider->getSource();

                return $provider->fetch();
            } catch (\Exception $ex) {
                continue;
            }
        }

        throw new \InvalidArgumentException('Providers is not valid');
    }

    public function getSource(): string
    {
        return (string) $this->source;
    }
}
