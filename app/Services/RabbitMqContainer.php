<?php

namespace App\Service;

use App\Service\Channel\ChannelProvider;
use App\Service\Connection\ConnectionProvider;
use App\Service\Exchange\ExchangeProvider;
use App\Service\Queue\QueueProvider;


class RabbitMqContainer extends Container
{
    /**
     * 服务提供者
     *
     * @var array
     */
    protected $providers = [
        ConnectionProvider::class,
        ChannelProvider::class,
        ExchangeProvider::class,
        QueueProvider::class
    ];

}