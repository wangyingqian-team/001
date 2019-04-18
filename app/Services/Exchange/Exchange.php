<?php
namespace App\Service\Exchange;

use App\Service\Channel\Channel;
use App\Service\RabbitMqContainer;
use PhpAmqpLib\Channel\AMQPChannel;

class Exchange
{
    protected $container;

    protected $exchange;

    protected $exchangeName;

    protected $type = 'direct';

    protected $passive = false;

    protected $durable = false;

    protected $autoDelete = false;

    protected $internal = false;

    protected $nowait = false;

    protected $arguments = [];

    protected $ticket = null;

    public function __construct(RabbitMqContainer $container)
    {
        $this->container = $container;
    }

    public function declareExchange($exchangeName)
    {
        $this->exchangeName = $exchangeName;

        $this->exchange = $this->container[Channel::class]->getChannel()->exchange_declare(
            $this->exchangeName,
            $this->type,
            $this->passive,
            $this->durable,
            $this->autoDelete,
            $this->internal,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );


    }

    public function setType(string $type)
    {
        $types = [
            'direct','fanout', 'topic'
        ];

        if (!in_array($type, $types)){
            throw new \InvalidArgumentException('xxx');
        }

        $this->type = $type;
    }

    public function setPassive(bool $passive)
    {
        $this->passive = $passive;
    }

    public function setDurable(bool $durable)
    {
        $this->durable = $durable;
    }

    public function setAutoDelete(bool $autoDelete)
    {
        $this->autoDelete = $autoDelete;
    }

    public function setInternal(bool $internal){

        $this->internal = $internal;
    }

    public function setNowait(bool $nowait)
    {
        $this->nowait = $nowait;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }

    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }

    public function getExchange()
    {
        return $this->exchange;
    }

    public function getExchangeName()
    {
        return $this->exchangeName;
    }
}