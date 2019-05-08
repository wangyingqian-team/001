<?php
namespace Liviator\Whisper\Concern;

use Illuminate\Support\Facades\Log;
use Lawoole\Homer\HomerException;
use Lawoole\Homer\Invocation;
use Lawoole\Homer\Transport\TransportException;
use Throwable;

trait ClientRequest
{
    /**
     * 获得超时
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->config['timeout'] ?? 5000;
    }

    /**
     * 获得连接失败重试次数
     *
     * @return int
     */
    public function getRetryTimes()
    {
        return $this->config['retry_times'] ?? 0;
    }

    /**
     * 发送消息请求
     *
     * @param mixed $message
     *
     * @return mixed
     */
    public function request($message)
    {
        try {
            $body = $this->serializer->serialize($message);

            $startTime = microtime(true);

            $data = $this->executeRequest($body);

            if ($this->isDebug() && $message instanceof Invocation) {
                $useTime = (microtime(true) - $startTime) * 1000;

                if ($useTime > $this->getSlowlogTimeout()) {
                    Log::channel('homer')->warning("请求处理过慢：{$message->getInterface()}->{$message->getMethod()}", [
                        'use_time' => $useTime,
                    ]);
                }
            }

            return $this->serializer->unserialize($data);
        } catch (HomerException $e) {
            $this->disconnect();

            throw $e;
        } catch (Throwable $e) {
            $this->disconnect();

            Log::channel('homer')->warning('Send rpc request failed', [
                'exception' => $e
            ]);

            throw new TransportException('Send rpc request failed, cause: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * 执行请求
     *
     * @param string $body
     *
     * @return string
     */
    protected function executeRequest($body)
    {
        $retryTimes = $this->getRetryTimes();

        do {
            $this->reconnectIfLostConnection();

            try {
                return $this->doRequest($body);
            } catch (TransportException $e) {
                $this->disconnect();

                if ($e->isConnection() && $retryTimes-- > 0) {
                    continue;
                }

                throw $e;
            }
        } while (true);
    }

    /**
     * 是否开启调试
     *
     * @return bool
     */
    protected function isDebug()
    {
        return $this->config['debug'] ?? false;
    }

    /**
     * 获得慢日志时间
     *
     * @return bool
     */
    protected function getSlowlogTimeout()
    {
        return $this->config['slowlog_timeout'] ?? 1000;
    }
}