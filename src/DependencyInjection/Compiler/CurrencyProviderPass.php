<?php

namespace App\DependencyInjection\Compiler;

use App\CurrencyProvider\CurrencyContext;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CurrencyProviderPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(CurrencyContext::class)) {
            return;
        }

        $definition = $container->findDefinition(CurrencyContext::class);

        $tag = new TaggedIteratorArgument('app.currency_provider');
        $taggedServices = $this->findAndSortTaggedServices($tag, $container);

        foreach ($taggedServices as $service) {
            $definition->addMethodCall('addProvider', [$service]);
        }
    }
}
