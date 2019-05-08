<?php
namespace Liviator\Whisper\Transport\Whisper;

use Illuminate\Support\Facades\Log;
use Lawoole\Homer\Transport\Whisper\WhisperServerSocketHandler as BaseWhisperServerSocketHandler;
use Liviator\Whisper\Concern\ClearTransaction;
use Throwable;

class WhisperServerSocketHandler extends BaseWhisperServerSocketHandler
{
    use ClearTransaction;

    /**
     * 从连接中取得数据时调用
     *
     * @param \Lawoole\Server\Server $server
     * @param \Lawoole\Server\ServerSockets\ServerSocket $serverSocket
     * @param int $fd
     * @param int $reactorId
     * @param string $data
     */
    public function onReceive($server, $serverSocket, $fd, $reactorId, $data)
    {
        try {
            $serializer = $this->getSerializer($serverSocket);

            $message = $serializer->unserialize(substr($data, 8));

            $result = $this->dispatcher->handleMessage($message);

            $body = $serializer->serialize($result);

            $this->respond($server, $fd, 200, $body);
        } catch (Throwable $e) {
            Log::channel('homer')->warning('Handle invoking failed, cause: '.$e->getMessage(), [
                'exception' => $e
            ]);

            $this->respond($server, $fd, 500, $e->getMessage());

            $server->closeConnection($fd);
        }

        $this->clearTransaction($this->app);
    }

    /**
     * 发送响应
     *
     * @param \Lawoole\Server\Server $server
     * @param int $fd
     * @param int $status
     * @param string $body
     */
    protected function respond($server, $fd, $status, $body)
    {
        $swooleServer = $server->getSwooleServer();

        $swooleServer->send($fd, 'wsp:'.pack('NN', $status, strlen($body)));
        $swooleServer->send($fd, $body);
    }
}