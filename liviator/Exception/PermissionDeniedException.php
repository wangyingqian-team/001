<?php
namespace Liviator\Exception;

use Throwable;

/**
 * 权限校验异常
 *
 * @author You Ming
 */
class PermissionDeniedException extends LiviatorException
{
    /**
     * 缺失的权限
     *
     * @var array
     */
    protected $permissions;

    /**
     * 创建权限校验异常
     *
     * @param string $message
     * @param array $permissions
     * @param \Throwable $previous
     */
    public function __construct($message = 'Permission Denied', array $permissions = [], Throwable $previous = null)
    {
        parent::__construct($message, 403, $previous);

        $this->permissions = $permissions;
    }

    /**
     * 获得缺失的权限
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * 设置缺失的权限
     *
     * @param array $permissions
     *
     * @return \Liviator\Exception\PermissionDeniedException
     */
    public function setPermissions(array $permissions)
    {
        $this->permissions = $permissions;

        return $this;
    }
}
