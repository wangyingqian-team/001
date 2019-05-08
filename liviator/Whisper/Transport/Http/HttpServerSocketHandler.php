<?php
namespace Liviator\Whisper\Transport\Http;

use Lawoole\Homer\Transport\Http\HttpServerSocketHandler as BaseHttpServerSocketHandler;
use Liviator\Whisper\Concern\ClearTransaction;

class HttpServerSocketHandler extends BaseHttpServerSocketHandler
{
    use ClearTransaction;

    /**
     * 收到 Http 处理请求时调用
     *
     * @param \Lawoole\Server\Server $server
     * @param \Lawoole\Server\ServerSockets\ServerSocket $serverSocket
     * @param \Swoole\Http\Request $request
     * @param \Swoole\Http\Response $response
     */
    public function onRequest($server, $serverSocket, $request, $response)
    {
        parent::onRequest($server, $serverSocket, $request, $response);

        $this->clearTransaction($this->app);
    }
}