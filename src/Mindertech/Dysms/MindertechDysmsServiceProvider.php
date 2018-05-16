<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:25
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;
use Illuminate\Support\ServiceProvider;

use Mindertech\Dysms\SendSmsRequest;
use Mindertech\Dysms\QuerySendDetailsRequest;
use Mindertech\Dysms\SmsQueueRequest;
use Mindertech\Dysms\SendBatchSmsRequest;

class MindertechDysmsServiceProvider extends ServiceProvider {
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../../config/dysms.php' => config_path('dysms.php')
        ]);
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/dysms.php', 'dysms'
        );

        $this->registerDysms();
    }



    private function registerDysms() {
        //https://help.aliyun.com/document_detail/55451.html
        $this->app->bind('send-sms', function ($app) {
            return new SendSmsRequest($app);
        });
        $this->app->alias('send-sms', 'Mindertech\Dysms\SendSmsRequest');

        //https://help.aliyun.com/document_detail/55452.html
        $this->app->bind('query-sms', function($app) {
            return new QuerySendDetailsRequest($app);
        });
        $this->app->alias('query-sms', 'Mindertech\Dysms\QuerySendDetailsRequest');

        //https://help.aliyun.com/document_detail/66262.html
        $this->app->bind('send-sms-batch', function($app) {
            return new SendBatchSmsRequest($app);
        });
        $this->app->alias('send-sms-batch', 'Mindertech\Dysms\SendBatchSmsRequest');

        //https://help.aliyun.com/document_detail/55500.html
        $this->app->bind('sms-queue', function($app) {
            return new SmsQueueRequest($app);
        });
        $this->app->alias('sms-queue', 'Mindertech\Dysms\SmsQueueRequest');

    }
}