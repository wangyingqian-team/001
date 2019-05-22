<?php
namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Supports\AliOss;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class IndexController extends Controller
{
    public function index(Request $request)
    {
       $redis = Redis::connection();
        dd($redis->get('a'));

    }

    public function test(Request $request)
    {
        $code = $request->get('code');
Log::info('wx_code', [
    'code' => $code
]);
        // 公众号配置
        $appId     = 'wxd3e0369a1545421f';
        $appSecret = 'QKiZDzlfu3dlciZGsjUgDll8tLh1cvvY';

        $urlAccessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appId."&secret=".$appSecret."&code=".$code."&grant_type=authorization_code";

        $res = $this->httpCurlAction($urlAccessToken,'get');
Log::info('wx_res', [
    'res' => $res
]);
        var_dump($res);die;
    }

    private function httpCurlAction($url, $type = 'get', $res = 'json', $arr = '')
    {
        //初始化curl
        $ch = curl_init();
        //设置curl的参数
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($type == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        }
        //采集
        $output = curl_exec($ch);
        //关闭
        //    curl_close($ch);
        if ($res == 'json') {
            //        echo curl_errno($ch);
            if (curl_errno($ch)) {
                //请求失败 返回错误信息
                return curl_errno($ch);
            } else {
                return json_decode($output, true);
            }

        }
        var_dump($output);
    }
}