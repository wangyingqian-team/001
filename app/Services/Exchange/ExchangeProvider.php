<?php
namespace App\Service\Exchange;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ExchangeProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[Exchange::class] = function ($container){
            return new Exchange($container);
        };
    }
}