<?php
namespace Liviator\Exception;

use Exception;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Lawoole\Foundation\Exceptions\Handler as ExceptionHandler;
use Liviator\Restful\RestfulException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * 不需要报告的异常
     *
     * @var array
     */
    protected $dontReport = [
        RestfulException::class,
        LiviatorException::class,
        ValidationException::class,
        HttpException::class,
        HttpResponseException::class,
    ];

    /**
     * 渲染异常到 Http 响应
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof RestfulException) {
            //
        } elseif ($e instanceof LiviatorException) {
            $e = RestfulException::convertLiviatorException($e);
        } elseif ($e instanceof ValidationException) {
            $e = RestfulException::convertValidationException($e);
        } elseif ($e instanceof HttpException || $e instanceof HttpResponseException) {
            $e = RestfulException::convertHttpException($e);
        } else {
            $e = RestfulException::convertException($e);
        }

        return $e->toResponse();
    }

    /**
     * 渲染异常到控制台输出
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception $e
     */
    public function renderForConsole($output, Exception $e)
    {
        if ($e instanceof ValidationException) {
            $e = new LiviatorException(
                $e->validator->errors()->first() ?? $e->getMessage(),
                $e->getCode(), $e
            );
        }

        parent::renderForConsole($output, $e);
    }
}
