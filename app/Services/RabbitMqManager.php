<?php
namespace App\Service;

use App\Service\Channel\Channel;
use App\Service\Connection\Connection;
use App\Service\Exchange\Exchange;
use App\Service\Queue\Queue;
use Illuminate\Support\Str;
use Pimple\Container;

class RabbitMqManager
{

    protected $container;

    protected $config  = [
                'host' => '127.0.0.1',
                'port' => 5672,
                'user' => 'guest',
                'password' => 'guest'
            ];

    protected $connection;

    protected $channel;

    protected $exchange;

    protected $queue;


    public function __construct($config = [])
    {
        if (!$this->container instanceof Container){
            $this->container = new RabbitMqContainer();
        }

        $this->config = $config;
        $this->connection = $this->container[Connection::class];
        $this->channel = $this->container[Channel::class];
        $this->exchange = $this->container[Exchange::class];
        $this->queue = $this->container[Queue::class];
    }

    /**
     * 发布消息
     *
     * @param string $msg
     * @param string $exchangeName
     *
     * @param string $routeKey
     */
    public function publish($msg = 'test', $exchangeName = 'test', $routeKey = '')
    {
        $this->connect();

        $this->channel->publish($msg, $exchangeName, $routeKey);

        $this->disconnect();
    }

    /**
     * 消费消息
     *
     * @param string $queueName
     * @param string $exchangeName
     *
     * @param null $routeKey
     */
    public function consume($queueName = 'q', $exchangeName = 'test',  $routeKey = null)
    {
        $this->connect();

        echo 'waiting receive...'."\n";

        $this->channel->consume($queueName, $exchangeName, $routeKey);

        $this->disconnect();
    }

    public function setChannel(array $config = [])
    {
        foreach ($config as $key => $value){
            $this->channel->{Str::camel('set'.$key)}($value);
        }
    }

    public function setExchange(array $config = [])
    {
        foreach ($config as $key => $value){
            $this->exchange->{Str::camel('set'.$key)}($value);
        }
    }

    public function setQueue(array $config = [])
    {
        foreach ($config as $key => $value){
            $this->queue->{Str::camel('set'.$key)}($value);
        }
    }

    protected function connect()
    {
        $this->connection->connect($this->config, $this->channel);
    }

    protected function disconnect()
    {
        $this->connection->disconnect();
    }

}