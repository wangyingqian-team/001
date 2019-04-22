<?php
namespace App\Exceptions;

use Throwable;

/**
 * 操作失败
 *
 * @author googol24
 */
class OperationFailedException extends DineException
{
    /**
     * 操作名
     *
     * @var string
     */
    protected $operation;

    /**
     * 创建操作失败异常
     *
     * @param string $message
     * @param \Throwable $previous
     */
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }

    /**
     * 获得操作名
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * 设置操作名
     *
     * @param string $operation
     *
     * @return \App\Exceptions\OperationFailedException
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }
}