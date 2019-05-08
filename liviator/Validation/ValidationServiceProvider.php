<?php
namespace Liviator\Validation;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;

/**
 * Class ValidationServiceProvider
 *
 * @author You Ming
 */
class ValidationServiceProvider extends ServiceProvider
{
    /**
     * 注册服务提供者
     */
    public function register()
    {
        $this->registerPresenceVerifier();

        $this->registerValidationFactory();
    }

    /**
     * 注册验证器工厂
     */
    protected function registerValidationFactory()
    {
        $this->app->singleton('validator', function ($app) {
            $validator = new Factory($app['translator'], $app);

            if (isset($app['db'], $app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            $validator->resolver(function($translator, $data, $rules, $messages, $customAttributes) {
                return $this->createValidator($translator, $data, $rules, $messages, $customAttributes);
            });

            return $validator;
        });
    }

    /**
     * 创建验证器
     *
     * @param \Illuminate\Contracts\Translation\Translator $translator
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Liviator\Validation\Validator
     */
    protected function createValidator($translator, $data, $rules, $messages, $customAttributes)
    {
        return new Validator($translator, $data, $rules, $messages, $customAttributes);
    }

    /**
     * 注册数据库验证器
     */
    protected function registerPresenceVerifier()
    {
        $this->app->singleton('validation.presence', function ($app) {
            return new DatabasePresenceVerifier($app['db']);
        });
    }

    /**
     * 获得服务提供者所提供的服务
     *
     * @return array
     */
    public function provides()
    {
        return ['validator', 'validation.presence'];
    }
}
