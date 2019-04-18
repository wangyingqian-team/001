<?php
namespace App\Service\Connection;

use App\Service\Channel\Channel;
use App\Service\RabbitMqContainer;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class Connection
{
    protected $container;

    protected $connection;

    public function __construct(RabbitMqContainer $container)
    {
        $this->container = $container;
    }

    public function connect($config)
    {
        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password']
        );

        $this->container[Channel::class]->connect($this->connection);
    }

    public function disconnect()
    {
        $this->connection->close();

        $this->container[Channel::class]->disconnect();
    }
}