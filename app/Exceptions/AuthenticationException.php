<?php
namespace App\Exceptions;

use Throwable;

/**
 * 身份校验失败
 *
 * @author googol24
 */
class AuthenticationException extends DineException
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