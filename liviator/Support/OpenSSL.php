<?php
namespace Liviator\Support;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * OpenSSL 加解密工具
 *
 * @author You Ming
 */
class OpenSSL
{
    /**
     * 创建密钥对
     *
     * @param int $type
     * @param int $bits
     *
     * @return array
     */
    public static function createKey($type = OPENSSL_KEYTYPE_RSA, $bits = 1024)
    {
        $key = openssl_pkey_new([
            'private_key_type' => $type,
            'private_key_bits' => $bits
        ]);

        if (!is_resource($key)) {
            throw new InvalidArgumentException('密钥对生成失败');
        }

        openssl_pkey_export($key, $private);

        $details = openssl_pkey_get_details($key);

        $public = $details['key'];

        return compact('public', 'private');
    }

    /**
     * 获得私钥信息
     *
     * @param string $privateKey
     * @param string $passPhrase
     *
     * @return array
     */
    public static function openPrivateKey($privateKey, $passPhrase = '')
    {
        $key = openssl_pkey_get_private($privateKey, $passPhrase);

        if (!is_resource($key)) {
            throw new InvalidArgumentException('传入的私钥不是合法私钥');
        }

        return openssl_pkey_get_details($key);
    }

    /**
     * 获得公钥信息
     *
     * @param string $publicKey
     *
     * @return array
     */
    public static function openPublicKey($publicKey)
    {
        $key = openssl_pkey_get_public($publicKey);

        if (!is_resource($key)) {
            throw new InvalidArgumentException('传入的公钥不是合法公钥');
        }

        return openssl_pkey_get_details($key);
    }

    /**
     * 私钥加密
     *
     * @param string $data
     * @param string $privateKey
     * @param int $padding
     *
     * @return bool|string
     */
    public static function privateKeyEncrypt($data, $privateKey, $padding = OPENSSL_PKCS1_PADDING)
    {
        $key = static::openPrivateKey($privateKey);

        $split = $key['bits'] / 8 - ($padding == OPENSSL_PKCS1_PADDING ? 11 : 0);

        $chunks = str_split($data, $split);

        $result = '';

        foreach ($chunks as $chunk) {
            $partial = '';

            $encrypted = openssl_private_encrypt($chunk, $partial, $privateKey, $padding);

            if ($encrypted == false) {
                return false;
            }

            $result .= $partial;
        }

        return $result;
    }

    /**
     * 公钥解密
     *
     * @param string $data
     * @param string $publicKey
     * @param int $padding
     *
     * @return bool|string
     */
    public static function publicKeyDecrypt($data, $publicKey, $padding = OPENSSL_PKCS1_PADDING)
    {
        $key = static::openPublicKey($publicKey);

        $split = $key['bits'] / 8;

        $chunks = str_split($data, $split);

        $result = '';

        foreach ($chunks as $chunk) {
            $partial = '';

            $decrypted = openssl_public_decrypt($chunk, $partial, $publicKey, $padding);

            if ($decrypted == false) {
                return false;
            }

            $result .= $partial;
        }

        return $result;
    }

    /**
     * 公钥加密
     *
     * @param string $data
     * @param string $publicKey
     * @param int $padding
     *
     * @return bool|string
     */
    public static function publicKeyEncrypt($data, $publicKey, $padding = OPENSSL_PKCS1_PADDING)
    {
        $key = static::openPublicKey($publicKey);

        $split = $key['bits'] / 8 - ($padding == OPENSSL_PKCS1_PADDING ? 11 : 0);

        $chunks = str_split($data, $split);

        $result = '';

        foreach ($chunks as $chunk) {
            $partial = '';

            $encrypted = openssl_public_encrypt($chunk, $partial, $publicKey, $padding);

            if ($encrypted == false) {
                return false;
            }

            $result .= $partial;
        }

        return $result;
    }

    /**
     * 私钥解密
     *
     * @param string $data
     * @param string $privateKey
     * @param int $padding
     *
     * @return bool|string
     */
    public static function privateKeyDecrypt($data, $privateKey, $padding = OPENSSL_PKCS1_PADDING)
    {
        $key = static::openPrivateKey($privateKey);

        $split = $key['bits'] / 8;

        $chunks = str_split($data, $split);

        $result = '';

        foreach ($chunks as $chunk) {
            $partial = '';

            $decrypted = openssl_private_decrypt($chunk, $partial, $privateKey, $padding);

            if ($decrypted == false) {
                return false;
            }

            $result .= $partial;
        }

        return $result;
    }

    /**
     * 获得默认初始向量值
     *
     * @param string $cipher 方法
     * @param string $key 密钥
     *
     * @return string
     */
    protected static function getDefaultInitVector($cipher, $key)
    {
        if (Str::contains(strtolower($cipher), ['ecb', 'des', 'rc2', 'rc4', 'md5'])) {
            return '';
        }

        $ivLength = openssl_cipher_iv_length($cipher);

        $springs = str_pad(dechex(crc32($key)), 8, '0', STR_PAD_LEFT);

        return substr(str_repeat($springs, ceil($ivLength / strlen($springs))), 0, $ivLength);
    }

    /**
     * 加密数据
     *
     * @param string $cipher 方法
     * @param string $key 密钥
     * @param string $data 数据
     * @param string $initVector 初始向量
     * @param int $options 参数
     *
     * @return string
     */
    public static function encrypt($cipher, $key, $data, $initVector = null, $options = 0)
    {
        if ($initVector == null) {
            $initVector = static::getDefaultInitVector($cipher, $key);
        }

        return openssl_encrypt($data, $cipher, $key, $options, $initVector);
    }

    /**
     * 解密数据
     *
     * @param string $cipher 方法
     * @param string $key 密钥
     * @param string $data 数据
     * @param string $initVector 初始向量
     * @param int $options 参数
     *
     * @return string
     */
    public static function decrypt($cipher, $key, $data, $initVector = null, $options = 0)
    {
        if ($initVector == null) {
            $initVector = static::getDefaultInitVector($cipher, $key);
        }

        return openssl_decrypt($data, $cipher, $key, $options, $initVector);
    }

    /**
     * DES-3 加密
     *
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    public static function des3Encrypt($data, $key)
    {
        return static::encrypt('DES-EDE3', $key, $data);
    }

    /**
     * DES-3 解密
     *
     * @param string $data
     * @param string $key
     *
     * @return string
     */
    public static function des3Decrypt($data, $key)
    {
        return static::decrypt('DES-EDE3', $key, $data);
    }
}