<?php
namespace Liviator\Exception;

use Throwable;

/**
 * 身份校验失败
 *
 * @author You Ming
 */
class AuthenticationException extends LiviatorException
{
    /**
     * 创建身份校验异常
     *
     * @param string $message
     * @param \Throwable $previous
     */
    public function __construct($message = 'Unauthenticated', Throwable $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}
