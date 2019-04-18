<?php
namespace App\Service\Connection;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConnectionProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[Connection::class] = function ($container){
            return new Connection($container);
        };
    }
}