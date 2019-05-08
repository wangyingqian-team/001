<?php
namespace Liviator\Support;

use InvalidArgumentException;
use LengthException;

/**
 * 四则运算基础类
 * 主要基于PHP BC数学函数 http://php.net/manual/zh/ref.bc.php
 *
 * @author lky
 */
class MathOperation
{
    /**
     * 尾数处理：四舍五入
     */
    const ROUND = 'round';

    /**
     * 尾数处理：向上取整
     */
    const CEIL = 'ceil';

    /**
     * 尾数处理：向下取整
     */
    const FLOOR = 'floor';

    /**
     * 加法
     *
     * @param string $addendOne
     * @param string $addendTwo
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function plus($addendOne = null, $addendTwo = null, $decimals = 3, $type = self::ROUND)
    {
        return static::plusArray([$addendOne, $addendTwo], $decimals, $type);
    }

    /**
     * 数组加法，数组第一位依次加上后面的数值
     *
     * @param array $numbers
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function plusArray($numbers = [], $decimals = 3, $type = self::ROUND)
    {
        $numbers = static::validateArray($numbers);

        $rs = 0;

        foreach ($numbers as $n) {
            static::pregMatch($n);

            $rs = bcadd($rs, trim($n), $decimals + 1);
        }

        $rs = static::formatNumber($rs, $decimals, $type);

        return $rs;
    }

    /**
     * 数组减法
     *
     * @param string $minuend
     * @param string $subtrahend
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function minus($minuend, $subtrahend, $decimals = 3, $type = self::ROUND)
    {
        return static::minusArray([$minuend, $subtrahend], $decimals, $type);
    }

    /**
     * 数组减法，数组第一位依次减去后面的数值
     *
     * @param array $numbers
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function minusArray($numbers = [], $decimals = 3, $type = self::ROUND)
    {
        $numbers = static::validateArray($numbers);

        $rs = array_shift($numbers);

        static::pregMatch($rs);

        foreach ($numbers as $n) {
            static::pregMatch($n);

            $rs = bcsub($rs, trim($n), $decimals + 1);
        }

        $rs = static::formatNumber($rs, $decimals, $type);

        return $rs;
    }

    /**
     * 乘法
     *
     * @param string $factorOne
     * @param string $factorTwo
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function multiply($factorOne, $factorTwo, $decimals = 3, $type = self::ROUND)
    {
        return static::multiplyArray([$factorOne, $factorTwo], $decimals, $type);
    }

    /**
     * 数组乘法，数组内值的积
     *
     * @param array $numbers
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function multiplyArray($numbers = [], $decimals = 3, $type = self::ROUND)
    {
        $numbers = static::validateArray($numbers);

        if (in_array(0, $numbers)) {
            return sprintf("%.{$decimals}f", 0);
        }

        $rs = static::multiplyOperation($numbers, $decimals + 2);

        $rs = static::formatNumber($rs, $decimals, $type);

        return $rs;
    }

    /**
     * 除法
     *
     * @param string $dividend
     * @param string $divisor
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function div($dividend, $divisor, $decimals = 3, $type = self::ROUND)
    {
        return static::divArray([$dividend, $divisor], $decimals, $type);
    }


    /**
     * 数组除法，数组第一位做分子，第二位依次做分母
     *
     * @param array $numbers
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function divArray($numbers = [], $decimals = 3, $type = self::ROUND)
    {
        $numbers = static::validateArray($numbers);

        if (head($numbers) === 0) {
            return sprintf("%.{$decimals}f", 0);
        }

        if (in_array(0, $numbers)) {
            throw new InvalidArgumentException('分母不可为 0');
        }

        $rs = array_shift($numbers);

        static::pregMatch($rs);

        foreach ($numbers as $n) {
            static::pregMatch($n);

            $rs = bcdiv($rs, trim($n), $decimals + 1);
        }

        $rs = static::formatNumber($rs, $decimals, $type);

        return $rs;
    }


    /**
     * 数字规则化
     *
     * @param string $number
     * @param int $decimals
     * @param string $type
     *
     * @return string
     */
    public static function formatNumber($number = null, $decimals = 3, $type = self::ROUND)
    {
        if (!in_array($type, [self::ROUND, self::CEIL, self::FLOOR])) {
            throw new InvalidArgumentException('使用了不支持的处理类型');
        }

        if ($number === null || $decimals < 0) {
            throw new InvalidArgumentException('规则参数不正确或数字对象为空');
        }

        $powDecimals = pow(10, $decimals);

        $result = call_user_func($type, static::multiplyOperation([$number, $powDecimals], $decimals + 1));

        return bcdiv($result, $powDecimals, $decimals);
    }

    /**
     * 四则运算数组校验
     *
     * @param array $dataArray
     *
     * @return array
     */
    protected static function validateArray($dataArray)
    {
        if ($dataArray === [] || !is_array($dataArray)) {
            throw new InvalidArgumentException('运算数组对象为空或者不为数组');
        }

        if (count($dataArray) < 2) {
            throw new LengthException('运算数组元素数量不够');
        }

        return $dataArray;
    }

    /**
     * 数组内元素乘法运算
     *
     * @param array $numbers
     * @param int $decimals
     *
     * @return string
     */
    protected static function multiplyOperation(array $numbers, $decimals = 3)
    {
        $rs = 1;

        foreach ($numbers as $n) {
            static::pregMatch($n);

            $rs = bcmul($rs, trim($n), $decimals);
        }

        return $rs;
    }

    /**
     * 数组内元素乘法运算
     *
     * @param string $number
     *
     * @return bool
     */
    protected static function pregMatch($number)
    {
        if (!preg_match('/^(-?\d+)(\.\d+)?$/', $number)) {
            throw new InvalidArgumentException('不正确的数据格式');
        }

        return true;
    }
}
