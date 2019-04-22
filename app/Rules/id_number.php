<?php

namespace App\Rules;

use Datetime;
use Illuminate\Contracts\Validation\Rule;
use Throwable;

/**
 * ID card No. validation
 *
 * @author zhangzhengkun
 */
class id_number implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
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
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '身份证号码格式不正确！';
    }
}
