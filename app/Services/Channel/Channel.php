<?php
namespace App\Service\Channel;

use App\Service\Exchange\Exchange;
use App\Service\Queue\Queue;
use App\Service\RabbitMqContainer;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Channel
{
    protected $container;

    protected $channelId = null;

    protected $channel;

    protected $mandatory = false;

    protected $immediate = false;

    protected $consumerTag = '';

    protected $noLocal  =false;

    protected $noAck = true;

    protected $exclusive = false;

    protected $nowait = false;

    protected $callback = null;

    protected $arguments = [];

    protected $ticket = null;

    public function __construct(RabbitMqContainer $container)
    {
        $this->container = $container;
    }

    /**
     * 创建连接
     *
     * @param AbstractConnection $connection
     */
    public function connect(AbstractConnection $connection)
    {
        $this->channel = $connection->channel($this->channelId);
    }

    /**
     * 关闭连接
     */
    public function disconnect()
    {
        $this->channel->close();
    }

    /**
     * 发布消息
     *
     * @param $msg
     * @param string $exchangeName
     *
     * @param string $routeKey
     */
    public function publish($msg, $exchangeName = '', $routeKey = '')
    {
        $this->channel->basic_publish(
            $this->getMessage($msg),
            $exchangeName,
            $routeKey,
            $this->mandatory,
            $this->immediate,
            $this->ticket
        );
    }

    /**
     * 消费消息
     *
     * @param $queueName
     * @param $exchangeName
     *
     * @param $rouKey
     */
    public function consume($queueName, $exchangeName, $rouKey)
    {
        $this->container[Exchange::class]->declareExchange($exchangeName);
        $this->container[Queue::class]->declareQueue($queueName);
        $this->container[Queue::class]->bind($queueName, $exchangeName, $rouKey);

        $this->callback = function ($msg){
            echo 'msg:'.$msg->body."\n";
        };

        $this->channel->basic_consume(
            $queueName,
            $this->consumerTag,
            $this->noLocal,
            $this->noAck,
            $this->exclusive,
            $this->nowait,
            $this->callback,
            $this->ticket,
            $this->arguments
        );

        while($this->channel->callbacks){
            $this->channel->wait();
        }
    }

    /**
     * 消费回调
     *
     * @param $class
     *
     * @param $method
     */
    public function callback($class, $method)
    {
        $this->callback = function ($msg) use ($class, $method){
            return call_user_func_array([$class, $method], [$msg]);
        };

    }

    public function setChannelId($channelId)
    {
        $this->channelId = $channelId;
    }

    public function getChannelId()
    {
        return $this->channelId;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    protected function getMessage($msg)
    {
        return new AMQPMessage($msg);
    }

}