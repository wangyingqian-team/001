<?php
namespace App\Caller;

use GuzzleHttp\Client;

/**
 * 基础 http 调用器
 *
 * @author zhangzhengkun
 */
class HttpCaller
{
    /**
     * GuzzleHttp 客户端
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * 创建 Http 调用器
     *
     * @param string $baseUri
     * @param float $timeout
     */
    public function __construct($baseUri = '', $timeout = 3.0)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => $timeout,
        ]);
    }

    /**
     * 发送请求
     *
     * 参数 $options 参考：
     *     http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function request($method, $uri = '', array $options = [])
    {
        return $this->client->request($method, $uri, $options);
    }

    /**
     * 发送异步请求
     *
     * 参数 $options 参考：
     *     http://docs.guzzlephp.org/en/stable/request-options.html
     *
     * @param string $method
     * @param string $uri
     * @param array $options
     *
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    protected function asyncRequest($method, $uri = '', array $options = [])
    {
        return $this->client->requestAsync($method, $uri, $options);
    }
}