<?php
namespace Liviator\Whisper\Transport\Whisper;

use Illuminate\Support\Facades\Log;
use Lawoole\Homer\Transport\TransportException;
use Lawoole\Homer\Transport\Whisper\WhisperClient as BaseWhisperClient;
use Liviator\Whisper\Concern\ClientRequest;
use Swoole\Client as SwooleClient;
use Throwable;

class WhisperClient extends BaseWhisperClient
{
    use ClientRequest;

    /**
     * 连接服务器
     */
    protected function doConnect()
    {
        try {
            $this->client = new SwooleClient(SWOOLE_TCP, SWOOLE_SOCK_SYNC);

            $this->client->set([
                'open_length_check'     => true,
                'package_max_length'    => 8388608,
                'package_length_type'   => 'N',
                'package_length_offset' => 8,
                'package_body_offset'   => 12,
            ]);

            $result = $this->client->connect($this->getHost(), $this->getPort(), $this->getTimeout() / 1000.0);

            if ($result == false) {
                throw new TransportException('Connect to server ['.$this->getRemoteAddress().'] failed, cause: '
                    .socket_strerror($this->client->errCode).'.');
            }
        } catch (TransportException $e) {
            throw $e;
        } catch (Throwable $e) {
            if ($this->causedByConnectionProblem($e)) {
                $this->disconnect();

                throw new TransportException($e->getMessage(), TransportException::CONNECTION, $e);
            }

            throw new TransportException($e->getMessage(), 0, $e);
        }
    }

    /**
     * 发送消息请求
     *
     * @param string $data
     *
     * @return string
     */
    protected function doRequest($data)
    {
        try {
            $this->send('wsp:'.pack('N', strlen($data)).$data);

            $data = $this->receive();

            $status = unpack('Nstatus', substr($data, 4, 4))['status'];
            $data = substr($data, 12);

            if ($status != 200) {
                throw new TransportException($data ?: 'Whisper request failed, status: '.$status,
                    TransportException::REMOTE);
            }

            return $data;
        } catch (TransportException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::channel('homer')->warning($e->getMessage(), [
                'exception' => $e
            ]);

            if ($this->causedByConnectionProblem($e)) {
                $this->disconnect();

                throw new TransportException($e->getMessage(), TransportException::CONNECTION, $e);
            }

            throw $e;
        }
    }

    /**
     * 接收消息
     *
     * @return string
     */
    protected function receive()
    {
        try {
            $startTime = microtime(true) * 1000;

            do {
                $data = @$this->client->recv();

                if ($data === false) {
                    $errorCode = $this->client->errCode;

                    if ($errorCode == 4) {
                        if (microtime(true) * 1000 - $startTime < $this->getTimeout()) {
                            continue;
                        }

                        throw new TransportException('Receive timeout in '.$this->getTimeout().' ms.',
                            TransportException::TIMEOUT);
                    }

                    if ($errorCode == 11) {
                        throw new TransportException('Receive timeout in '.$this->getTimeout().' ms.',
                            TransportException::TIMEOUT);
                    }

                    throw new TransportException('Receive data failed, cause: '.socket_strerror($errorCode).'.',
                        $errorCode);
                } elseif ($data === '') {
                    throw new TransportException('Receive data failed, cause the connection has been closed.',
                        TransportException::CONNECTION);
                }

                break;
            } while (true);
        } catch (TransportException $e) {
            throw $e;
        } catch (Throwable $e) {
            if ($this->causedByTimeout($e)) {
                $this->disconnect();

                throw new TransportException('Receive timeout in '.$this->getTimeout().' ms.',
                    TransportException::TIMEOUT, $e);
            }

            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $data;
    }
}