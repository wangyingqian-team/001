<?php
namespace Liviator\Restful;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Liviator\Exception\AuthenticationException;
use Liviator\Exception\LiviatorException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class RestfulException
 *
 * @author You Ming
 */
class RestfulException extends LiviatorException
{
    /**
     * 附加数据
     *
     * @var mixed
     */
    protected $data;

    /**
     * 响应状态码
     *
     * @var int
     */
    protected $status = 400;

    /**
     * 创建接口异常
     *
     * @param int $code
     * @param string $message
     * @param mixed $data
     * @param \Throwable $previous
     */
    public function __construct($code, $message, $data = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

    /**
     * 获得附加数据
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置响应状态码
     *
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * 获得响应状态码
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * 转换验证器异常
     *
     * @param \Illuminate\Validation\ValidationException $e
     *
     * @return static
     */
    public static function convertValidationException(ValidationException $e)
    {
        $errors = $e->validator->errors();
        $message = $errors->first() ?: $e->getMessage();

        $data = [
            'errors'    => $errors->messages(),
            'error_bag' => $e->errorBag
        ];

        $exception = new static($e->status, $message, $data, $e);
        $exception->setStatus($e->status);

        return $exception;
    }

    /**
     * 转换 Http 异常
     *
     * @param \Exception $e
     *
     * @return static
     */
    public static function convertHttpException(Exception $e)
    {
        if ($e instanceof HttpException) {
            $status = $e->getStatusCode();
            $content = $e->getMessage();
        } elseif ($e instanceof HttpResponseException) {
            $response = $e->getResponse();
            $status = $response->getStatusCode();
            $content = $response->getContent();
        } else {
            $status = 500;
            $content = $e->getMessage();
        }

        $message = isset(Response::$statusTexts[$status]) ? Response::$statusTexts[$status] : 'Unknown';

        $exception = new static($status, $message, $content, $e);
        $exception->setStatus($status);

        return $exception;
    }

    /**
     * 转换框架异常
     *
     * @param \Liviator\Exception\LiviatorException $e
     *
     * @return static
     */
    public static function convertLiviatorException(LiviatorException $e)
    {
        $code = $e->getCode() ?: 1000;
        $message = $e->getMessage();

        $exception = new static($code, $message, null, $e);

        if ($e instanceof AuthenticationException) {
            $exception->setStatus(401);
        }

        return $exception;
    }

    /**
     * 转换普通异常
     *
     * @param \Exception $e
     *
     * @return static
     */
    public static function convertException(Exception $e)
    {
        $message = config('app.debug') ? '!! DEBUG !! '.$e->getMessage() : '服务器未知错误';

        $exception = new static(500, $message, null, $e);
        $exception->setStatus(500);

        return $exception;
    }

    /**
     * 生成异常对象
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse()
    {
        return RestfulResponse::error(
            $this->getCode(),
            $this->getMessage(),
            $this->getData(),
            $this->getStatus(),
            $this->getPrevious()
        );
    }
}
