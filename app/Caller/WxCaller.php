<?php
namespace App\Caller;

use App\Exceptions\OperationFailedException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

/**
 * 微信接口调用器
 *
 * @author zhangzhengkun
 */
class WxCaller extends HttpCaller
{
    /**
     * 微信 APP_ID
     *
     * @var string
     */
    private $appId;

    /**
     * 微信 APP_SECRET
     *
     * @var string
     */
    private $appSecret;

    /**
     * 微信 ACCESS_TOKEN
     *
     * @var string
     */
    const ACCESS_TOKEN = 'wx:access_token';

    public function __construct($baseUrl = '', $timeout = 3.0)
    {
        // 设置微信应用appId和appSecret
        $this->appId     = Config::get('wx.app_id');
        $this->appSecret = Config::get('wx.app_secret');

        parent::__construct($baseUrl, $timeout);
    }

    /**
     * 获取微信ID
     *
     * @param $code
     *
     * @return mixed
     *
     */
    public function getWxOpenId($code)
    {
        $url = '/sns/jscode2session?appid=' . $this->appId . '&secret=' . $this->appSecret . '&js_code=' . $code . '&grant_type=authorization_code';

        // 请求微信获取openid接口
        $response = $this->request('GET', $url, ['timeout' => 6]);

        // 解析返回结果
        $body = json_decode($response->getBody(), true);

        // 判断是否有错误
        if (!empty($body['errcode'])) {

            Log::error($body['errmsg'], [
                'code'    => $code,
                'errcode' => $body['errcode']
            ]);

            throw new OperationFailedException("获取微信openid失败");
        }

        return $body['openid'] ?? "";
    }

    /**
     * 获取微信access_token
     *
     * @return mixed
     *
     */
    public function getAccessToken()
    {
        $accessToken = Redis::get(self::ACCESS_TOKEN);

        if (empty($accessToken)) {

            $url = 'cgi-bin/token?grant_type=client_credential&appid=' . $this->appId . '&secret=' . $this->appSecret;

            // 请求微信获取access_token接口
            $response = $this->request('GET', $url, ['timeout' => 6]);

            // 解析返回结果
            $body = json_decode($response->getBody(), true);

            // 判断是否有错误
            if (!empty($body['errcode'])) {
                Log::error($body['errmsg'], [
                    'errcode' => $body['errcode']
                ]);

                throw new OperationFailedException("获取微信access_token失败");
            }

            Redis::set(self::ACCESS_TOKEN, $body['access_token']);

            Redis::expire(self::ACCESS_TOKEN, $body['expires_in'] - 600);

            return $body['access_token'];

        } else {

            return $accessToken;

        }
    }
}