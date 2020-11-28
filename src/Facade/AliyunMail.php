<?php
namespace JlwGeneral\AliyunMailLaravel\Facade;

use Illuminate\Support\Facades\Facade;

class AliyunMail extends Facade{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'aliyun_mail';
    }
}