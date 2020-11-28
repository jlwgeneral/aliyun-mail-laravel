<?php

namespace JlwGeneral\AliyunMailLaravel;

use Illuminate\Support\ServiceProvider;

class AliyunMailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //$this->configPublish();
        $this->app->singleton('aliyun_mail', function ($app) {
            $config = $app->make('config')->get('aliyun_mail', [ ]);

            return new AliyunMail($config);
        });


    }

    /*public function provides()
    {
        return ['aliyun_mail']; // TODO: Change the autogenerated stub
    }*/

    /**
     * Register the config for publishing
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config/aliyun_mail.php' => config_path('aliyun_mail.php')]);
        } else {
            $this->app->configure('aliyun_mail');
        }
    }

}
