<?php
namespace Liviator\Whisper;

use Lawoole\Homer\HomerManager;
use Lawoole\Homer\HomerServiceProvider;
use Liviator\Whisper\Transport\ClientFactory;
use Liviator\Whisper\Transport\Whisper\WhisperServerSocket;

class WhisperServiceProvider extends HomerServiceProvider
{
    /**
     * 注册 Homer 管理器
     */
    protected function registerHomer()
    {
        $this->app->singleton('homer', function ($app) {
            return new HomerManager($app, $app['config']['whisper']);
        });
    }

    /**
     * 注册客户端工厂
     */
    protected function registerClientFactory()
    {
        $this->app->singleton('homer.factory.client', function ($app) {
            return new ClientFactory($app);
        });
    }

    /**
     * 注册服务 Socket
     */
    protected function registerServerSockets()
    {
        $this->app->instance('server.sockets.whisper', WhisperServerSocket::class);
    }

    /**
     * 启动服务
     */
    public function boot()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app['config'];

        $config->set('logging.channels.homer', $config->get('logging.channels.whisper'));

        parent::boot();
    }
}
