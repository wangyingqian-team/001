<?php
namespace Liviator;

use Illuminate\Contracts\Foundation\Application as ApplicationContract;
use Lawoole\Application as BaseApplication;

/**
 * Class Application
 *
 * @author You Ming
 */
class Application extends BaseApplication implements ApplicationContract
{
    /**
     * 框架版本号
     */
    const VERSION = '0.4.18';

    /**
     * 获得应用名
     *
     * @return string
     */
    public function name()
    {
        return 'The Liviator framework';
    }

    /**
     * 获得应用版本信息
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION.' (Lawoole 0.4.*)';
    }

    /**
     * 初始化容器
     */
    protected function bootstrapContainer()
    {
        parent::bootstrapContainer();

        $this->instance(Application::class, $this);
    }
}
