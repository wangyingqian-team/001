<?php
namespace Liviator\Exception;

use Throwable;

/**
 * 状态错误
 *
 * @author You Ming
 */
class IllegalStatusException extends LiviatorException
{
    /**
     * 当前状态
     *
     * @var int
     */
    protected $status;

    /**
     * 创建状态错误异常
     *
     * @param string $message
     * @param int $status
     * @param \Throwable $previous
     */
    public function __construct($message, $status = 0, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);

        $this->status = $status;
    }

    /**
     * 获得当前状态
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 设置当前状态
     *
     * @param int $status
     *
     * @return \Liviator\Exception\IllegalStatusException
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
}
