<?php
namespace Liviator\Restful;

use Illuminate\Http\Request;
use Liviator\Application;
use Liviator\Routing\Controller as BaseController;

/**
 * Class RestfulController
 *
 * @author You Ming
 */
class RestfulController extends BaseController
{
    /**
     * 服务容器
     *
     * @var \Liviator\Application
     */
    protected $app;

    /**
     * 创建接口控制器
     *
     * @param \Liviator\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 发送接口成功响应
     *
     * @param mixed $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function success($data = null)
    {
        return RestfulResponse::success($data);
    }
}
