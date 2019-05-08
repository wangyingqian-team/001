<?php
namespace Liviator\Whisper\Transport\Http;

use Illuminate\Support\Facades\Log;
use Lawoole\Homer\Transport\Http\HttpClient as BaseHttpClient;
use Lawoole\Homer\Transport\TransportException;
use Liviator\Whisper\Concern\ClientRequest;
use Throwable;

class HttpClient extends BaseHttpClient
{
    use ClientRequest;

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
            $response = $this->client->request('POST', '', [
                'expect' => false,
                'body'   => $data
            ]);

            if ($response->getStatusCode() != 200) {
                throw new TransportException($response->getBody()->getContents() ?: 'Http request failed, status: '
                    .$response->getStatusCode(), TransportException::REMOTE);
            }
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

            throw new TransportException($e->getMessage(), 0, $e);
        }

        return $response->getBody()->getContents();
    }
}
