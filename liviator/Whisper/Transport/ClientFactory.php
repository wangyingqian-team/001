<?php
namespace Liviator\Whisper\Transport;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Lawoole\Homer\Transport\ClientFactory as BaseClientFactory;
use Liviator\Whisper\Transport\Http\HttpClient;
use Liviator\Whisper\Transport\Whisper\WhisperClient;

class ClientFactory extends BaseClientFactory
{
    /**
     * 创建客户端
     *
     * @param array $config
     *
     * @return \Lawoole\Homer\Transport\Client
     */
    protected function createClient(array $config)
    {
        $url = Arr::pull($config, 'url');

        $urls = parse_url($url);

        $config['host'] = $urls['host'];
        $config['port'] = $urls['port'];

        switch ($urls['scheme']) {
            case 'http':
                return new HttpClient($this->app, $config);
            case 'whisper':
                return new WhisperClient($this->app, $config);
            default:
                throw new InvalidArgumentException('Protocol '.$urls['scheme'].' is not supported for Homer.');
        }
    }
}