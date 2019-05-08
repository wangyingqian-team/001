<?php
namespace Liviator\Exception;

use Throwable;

/**
 * 参数非法
 *
 * @author You Ming
 */
class IllegalArgumentException extends LiviatorException
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
     * @param \Throwable $previous
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
     * @return \Liviator\Exception\IllegalArgumentException
     */
    public function setArgument($argument)
    {
        $this->argument = $argument;

        return $this;
    }
}
