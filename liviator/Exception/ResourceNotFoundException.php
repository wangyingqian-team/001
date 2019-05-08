<?php
namespace Liviator\Exception;

use Throwable;

/**
 * 资源未找到
 *
 * @author You Ming
 */
class ResourceNotFoundException extends LiviatorException
{
    /**
     * 资源名
     *
     * @var string
     */
    protected $resource;

    /**
     * 创建资源未找到异常
     *
     * @param string $message
     * @param \Throwable $previous
     */
    public function __construct($message, Throwable $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }

    /**
     * 获得资源名
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * 设置资源名
     *
     * @param string $resource
     *
     * @return \Liviator\Exception\ResourceNotFoundException
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }
}
