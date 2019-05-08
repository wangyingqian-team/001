<?php
namespace Liviator\Restful\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Str;

/**
 * Class WrapRestfulRequest
 *
 * @author You Ming
 */
class WrapRestfulRequest
{
    /**
     * 容器
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $container;

    /**
     * 创建接口包裹中间件
     *
     * @param \Illuminate\Contracts\Container\Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * 包裹 Restful 请求
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param bool $asObject
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle($request, Closure $next, $asObject = false)
    {
        if (Str::startsWith($request->header('Content-Type'), 'application/json')
            && !in_array($request->getMethod(), ['GET', 'HEAD'])) {
            // 自带 Json 解析，不需要在单独配置 Json 解析中间件
            $request = $this->parseJsonRequest($request, $asObject);
        }

        return $next($request);
    }

    /**
     * 解析 Json 请求参数
     *
     * @param \Illuminate\Http\Request $request
     * @param bool $asObject
     *
     * @return \Illuminate\Http\Request
     */
    public function parseJsonRequest($request, $asObject)
    {
        $parameters = json_decode($request->getContent(), !$asObject);

        if (JSON_ERROR_NONE !== json_last_error()) {
            // 不报错，只是清除请求体参数
            // throw new InvalidArgumentException('Json string cannot decoded, error: '.json_last_error_msg());

            $request->request->replace();

            return $request;
        }

        $request->request->replace($parameters);

        return $request;
    }
}
