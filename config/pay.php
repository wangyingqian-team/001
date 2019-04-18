<?php

return [
    'alipay' => [
        // 支付宝分配的 APPID
        'app_id' => '2016073000123867',

        // 支付宝异步通知地址
        'notify_url' => '',

        // 支付成功后同步通知地址
        'return_url' => '',

        // 阿里公共密钥，验证签名时使用
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA7fIURB4YcT2LfWe5yPL17hwKib9CocU8intV9wgYb+0UC7TmCxE0LqzMvZqu+mZ0QH7OQ7POCYlQERHVkvYFOkg1qTceVeNEVwmmpXbm9szbPoxLFLpiAJ4fqibotd3bPdvAs1fD1im3bjJHPu6Z7o/R74wmQrE/R7Oy1kmToFoPBEimBeYRpHSZOqCoIbHRhVterwtlAVOmU/XGzuRVD7DucRUBQfbKOOxLCelT0yPH2g/MpFh0fydcGuFkwrlpNqY4Nu3LDbACDDmZGL93sdMV/44L/7tuvlquEVupv3qpxnLKQs8MPXTNXAdH7LGWUAnU9himszrg7NgNQk5j+wIDAQAB',

        // 自己的私钥，签名时使用
        'private_key' => 'MIIEpAIBAAKCAQEA7fIURB4YcT2LfWe5yPL17hwKib9CocU8intV9wgYb+0UC7TmCxE0LqzMvZqu+mZ0QH7OQ7POCYlQERHVkvYFOkg1qTceVeNEVwmmpXbm9szbPoxLFLpiAJ4fqibotd3bPdvAs1fD1im3bjJHPu6Z7o/R74wmQrE/R7Oy1kmToFoPBEimBeYRpHSZOqCoIbHRhVterwtlAVOmU/XGzuRVD7DucRUBQfbKOOxLCelT0yPH2g/MpFh0fydcGuFkwrlpNqY4Nu3LDbACDDmZGL93sdMV/44L/7tuvlquEVupv3qpxnLKQs8MPXTNXAdH7LGWUAnU9himszrg7NgNQk5j+wIDAQABAoIBACKw/7lqtd+UvIidHd4pZifAGN06cGmLiycZklAA8ycmZpzKVBva90Oy1+rw6YACfgKFOmduiKSlS3IhqoTRr7Nuobw5GAgnqWgTNSO8sTHcbj6xT6UHA5DZfP5ey+DwJq3fIzpCmn/X9zFuzSpkuTap607EnTNuCi7XCUTq10YyczNbVcc5mE32Rh+sb0Cz8hpYvTmBx8JF5GIY4uQCZhnFz6hPi7ZujCUSlaA3QC/JoFm+kTKA6O1hok3GN0qzXBfuiV/hUAFo09tU4JptVNzZ3aIREbuQG91MspN1k81eicuKRjbc8jD6HKeCVuWaSlvHQAEjNMQe4HqApR1Q+skCgYEA+TZpBw2usly9d3FNJyo1Ao/InqnfUzplHQwc5yh7cm72REgSz9ALawX0W4Ba/j6KVZiSf8XXpvyeHy5N4aQR2biw0zfm0rMIhr6PxduwOUA32slrvJRuZb5J0wOGqKW7nVGvyeM9CBuGTIKXmlPmz/FxMkIOdSZE6paRZvc0ypUCgYEA9G0cwd1p/tAnHw4WQjcxtwak5mFPgitOD1KVaTsnDcI/pmEUTdhrbGQc3Q5XetIXtuoYZvaBkXXtvoHgIuJawKy/bTyvQV7WmiMSIQ/WAgW4ot9cpHDZ5bsPi9M7uANCKmGF5RhaLfvuntAxbcd8aJhDft8kliWUPB7koIHcYE8CgYBJyEUiFHfzUKe7lCzeeo2FO6KO7wYycuh6yBpKid66i4WXw6rmIdcvkWy+JmtKOKPmIazF7YIia0o5OxFNy7CJQDgB4NwS53SPyB1y2875tDyVJushwuRIdSUQN0wH5EF+my+rWv63xsZlIojV5R9B017LHWmAX5spxPg5ftj/XQKBgQC45uXPPhjV+07s7jImayx/oVYTNV/5P5swei+uyGG1xdFyopPCg8pX17ACBbxlnBL2e0Z0dVv01vo/mG4e1Y8DnGq/Tx3g3MaJGai4PAuPwhY3l7K0bu5XHFgZVXUiscxW1Sl98hseGCweFa6etj7FvRGqI1HBB7KIfHJFfZdhfwKBgQDU4z2cPw4B0ooFr/z6HyDmFoaxnXS4Z+CLBhqq+gLqLVdsjObAbs+OIsIrAJ0ND3Fm7QTbyg6NkaNqL2FpM8C+cYiCH1/5w2awML94UTmRd39OU0Y0O2RCTgLpvZWM5HSNNev59B5eAYTSWYSmAxpiVdH/askFH17oap8WF8Vt6Q==',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/alipay.log'),
        //  'level' => 'debug'
        //  'type' => 'single', // optional, 可选 daily.
        //  'max_file' => 30,
        ],

        // optional，设置此参数，将进入沙箱模式
         'mode' => 'dev',
    ],

    'wechat' => [
        // 公众号 APPID
        'app_id' => env('WECHAT_APP_ID', ''),

        // 小程序 APPID
        'miniapp_id' => env('WECHAT_MINIAPP_ID', ''),

        // APP 引用的 appid
        'appid' => env('WECHAT_APPID', ''),

        // 微信支付分配的微信商户号
        'mch_id' => env('WECHAT_MCH_ID', ''),

        // 微信支付异步通知地址
        'notify_url' => '',

        // 微信支付签名秘钥
        'key' => env('WECHAT_KEY', ''),

        // 客户端证书路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_client' => '',

        // 客户端秘钥路径，退款、红包等需要用到。请填写绝对路径，linux 请确保权限问题。pem 格式。
        'cert_key' => '',

        // optional，默认 warning；日志路径为：sys_get_temp_dir().'/logs/yansongda.pay.log'
        'log' => [
            'file' => storage_path('logs/wechat.log'),
        //  'level' => 'debug'
        //  'type' => 'single', // optional, 可选 daily.
        //  'max_file' => 30,
        ],

        // optional
        // 'dev' 时为沙箱模式
        // 'hk' 时为东南亚节点
        // 'mode' => 'dev',
    ],
];
