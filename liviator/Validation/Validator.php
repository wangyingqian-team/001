<?php
namespace Liviator\Validation;

use DateTime;
use Illuminate\Validation\Validator as LaravelValidator;
use Throwable;

/**
 * 常规验证组件
 *
 * @author zhang zhengkun
 */
class Validator extends LaravelValidator
{
    /**
     * 验证身份证号是否合法
     *
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    protected function validateIdNumber($attribute, $value)
    {
        if (!preg_match('/^\d{17}(\d|X)$/i', $value) && !preg_match('/^\d{15}$/i', $value)) {
            return false;
        }

        // 地区验证
        $provinces = [
            11, 12, 13, 14, 15,
            21, 22, 23,
            31, 32, 33, 34, 35, 36, 37,
            41, 42, 43, 44, 45, 46,
            50, 51, 52, 53, 54,
            61, 62, 63, 64, 65,
            71,
            81, 82,
            91
        ];

        $province = substr($value, 0, 2);

        if (!in_array($province, $provinces)) {
            return false;
        }

        // 身份证长度
        $length = strlen($value);

        if ($length == 15) {
            $year = '19' . substr($value, 6, 2);
            $month = substr($value, 8, 2);
            $day = substr($value, 10, 2);
        } else {
            $year = substr($value, 6, 4);
            $month = substr($value, 10, 2);
            $day = substr($value, 12, 2);
        }

        // 判断生日范围
        if ($year < 1900 || $year > 2078) {
            return false;
        }

        if ($month < 1 || $month > 12) {
            return false;
        }

        try {
            $birthday = $year . '-' . $month . '-' . $day;
            if ((new DateTime($birthday))->format('Y-m-d') != $birthday) {
                return false;
            }
        } catch (Throwable $e) {
            return false;
        }

        if ($length == 18) {
            // 加权因子校验
            $verifyFactor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
            $verifyNumberX = ['1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'];
            $verifySum = 0;

            for ($i = 0; $i < 17; $i++) {
                $verifySum += (int)$value[$i] * $verifyFactor[$i];
            }

            if ($value[17] != $verifyNumberX[$verifySum % 11]) {
                return false;
            }
        }

        return true;
    }

    /**
     * 验证手机号是否合法
     *
     * @param string $attribute
     * @param string $value
     *
     * @return bool
     */
    protected function validateMobile($attribute, $value)
    {
        return preg_match('/^13\d{9}$|^14[5-9]\d{8}$|^15\d{9}$|^166\d{8}$|^17\d{9}$|^18\d{9}$|^19[8,9]\d{8}$/', $value);
    }

    /**
     * 验证小数点位数
     *
     * @param string $attribute
     * @param string $value
     * @param array $parameters
     *
     * @return bool
     */
    protected function validateDecimals($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'decimals');

        $value = rtrim($value, '0');

        $decimals = $parameters[0] + 1;

        return !preg_match('/\.[0-9]{'.$decimals.',}$/', $value);
    }
}
