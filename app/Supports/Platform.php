<?php
namespace App\Supports;

/**
 * 平台常量枚举
 *
 * @author zhangzhengkun
 */
class Platform
{
    /**
     * 平台标识：微信公众号
     */
    const PLATFORM_WX_PUBLIC = 'wx_public';

    /**
     * 平台标识：微信小程序
     */
    const PLATFORM_WX_APPLET = 'wx_applet';

    /**
     * 平台标识：支付宝小程序
     */
    const PLATFORM_ALI_APPLET = 'ali_applet';

    /**
     * 平台标识
     */
    const PLATFORMS = [
        self::PLATFORM_WX_PUBLIC,
        self::PLATFORM_WX_APPLET,
        self::PLATFORM_ALI_APPLET
    ];
}