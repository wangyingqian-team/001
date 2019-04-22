<?php
namespace App\Exceptions;

use Throwable;

/**
 * 参数非法异常
 *
 * @author googol24
 */
class IllegalArgumentException extends DineException
{
    /**
     * 参数名
     *
     * @var string
     */
    protected $argument;

    /**
     * 创建参数非法异常
     *
     * @param string $message
     * @param string $argument
     * @param Throwable $previous
     */
    public function __construct($message, $argument = null, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->argument = $argument;
    }

    /**
     * 获得参数名
     *
     * @return string
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * 设置参数名
     *
     * @param string $argument
     *
     * @return \App\Exceptions\IllegalArgumentException
     */
    public function setArgument($argument)
    {
        $this->argument = $argument;

        return $this;
    }
}