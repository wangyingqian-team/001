<?php
namespace App\Supports;

use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 阿里 oss
 *
 * Class AliOss
 *
 * @package App\Supports
 */
class AliOss
{
    public static function createBucket($bucket)
    {
        try {
            $ossClient = new OssClient(config('oss.id'), config('oss.secret'), config('oss.endpoint'));
            $ossClient->createBucket($bucket);
        } catch (OssException $e) {
            dd($e->getMessage());
        }
    }

    public static function upload($bucket, $object, $content)
    {
        try {
            $ossClient = new OssClient(config('oss.id'), config('oss.secret'), config('oss.endpoint'));

            $ossClient->putObject($bucket, $object, $content);
        } catch (OssException $e) {
            dd($e->getMessage());
        }
    }
}