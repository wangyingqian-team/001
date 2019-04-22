<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * cellphone number validation
 *
 * @author zhangzhengkun
 */
class mobile implements Rule
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
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match('/^13\d{9}$|^14[5-9]\d{8}$|^15\d{9}$|^16[5,6]\d{8}$|^17\d{9}$|^18\d{9}$|^19[1,8,9]\d{8}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '手机号码格式不正确！';
    }
}
