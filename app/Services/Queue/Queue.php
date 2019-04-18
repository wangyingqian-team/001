<?php
namespace App\Service\Queue;

use App\Service\Channel\Channel;
use App\Service\RabbitMqContainer;

class Queue
{
    protected $container;

    protected $queue;

    protected $queueName;

    protected $passive = false;

    protected $durable = false;

    protected $exclusive = false;

    protected $autoDelete = true;

    protected $nowait = false;

    protected $arguments = [];

    protected $ticket = null;

    public function __construct(RabbitMqContainer $container)
    {
        $this->container = $container;
    }

    public function declareQueue($queueName)
    {
        $this->queueName = $queueName;

        $this->queue = $this->container[Channel::class]->getChannel()->queue_declare(
            $queueName,
            $this->passive,
            $this->durable,
            $this->exclusive,
            $this->autoDelete,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );
    }

    public function bind($queueName, $exchangeName, $routeKey ='')
    {
        $this->container[Channel::class]->getChannel()->queue_bind(
            $queueName,
            $exchangeName,
            $routeKey,
            $this->nowait,
            $this->arguments,
            $this->ticket
        );
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

    public function setExclusive(bool $exclusive){

        $this->exclusive = $exclusive;
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

    public function getQueue()
    {
        return $this->queue;
    }

    public function getQueueName()
    {
        return $this->queueName;
    }
}