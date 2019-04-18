<?php
namespace App\Service\Queue;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class QueueProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[Queue::class] = function ($container){
            return new Queue($container);
        };
    }
}