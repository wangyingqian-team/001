<?php
namespace App\Service\Channel;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ChannelProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        $container[Channel::class] = function ($container){
            return new Channel($container);
        };
    }
}