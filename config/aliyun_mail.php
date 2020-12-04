<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Aliyun Mail Push Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for aliyun_mail resource sharing
    | You are free to adjust these settings as needed.
    |
    | To learn more: https://help.aliyun.com/document_detail/29434.html?spm=a2c4g.11186623.6.587.697f226eHInpX8
    |
    */

    'city' => 'hangzhou',
    'type' => 'SingleSendMail', // SingleSendMail | BatchSendMail | DescAccountSummary
    'api_url' => 'https://dm.aliyuncs.com/?Action=',

    /*
    |--------------------------------------------------------------------------
    | How select send service area
    |--------------------------------------------------------------------------
    |
    | If your sending program is deployed in China, it is recommended that you select East China 1 region.
    | If your sending program is deployed overseas, and the receiving address is mostly overseas
    | it is recommended that you use Singapore or Australia cluster.
    | If your sending program is deployed overseas and the address of your mail is mostly in China:
    | a) If your sender uses SMTP to send messages and is deployed in the United States or Singapore,
    | it is recommended that you use East China 1 region, which will automatically route to East China 1 node through the US or Singapore acceleration node.
    | b) In other cases, it is recommended that you use the Singapore area.
    |
    */
    'area' => [
        'hangzhou' => [
            'RegionId' => 'cn-hangzhou',
            'Host' => 'dm.aliyuncs.com',
            'Version' => '2015-11-23'
        ],
        'singapore' => [
            'RegionId' => 'ap-southeast-1',
            'Host' => 'dm.ap-southeast-1.aliyuncs.com',
            'Version' => '2017-06-22'
        ],
        'sydney' => [
            'RegionId' => 'ap-southeast-2',
            'Host' => 'dm.ap-southeast-2.aliyuncs.com',
            'Version' => '2017-06-22'
        ],
    ],

    'auth' => [
        'AccessKeyID' => env('AccessKeyID', ''),
        'AccessKeySecret ' => env('AccessKeySecret', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Public request params
    |--------------------------------------------------------------------------
    |
    */
    'public' => [
        'params' => [
            'Format' => 'json', //json|xml(default)
            'Timestamp' => now()->toIso8601ZuluString(),
            'SignatureVersion' => '1.0',
            'SignatureNonce' => md5(uniqid(mt_rand(), true)),
            'RegionId' => env('RegionId', ''),
            'SignatureMethod' => 'HMAC-SHA1',

            //'AccessKeyId' => env('AccessKeyId', ''),
            //'Version' => '2015-11-23',
            //'Signature' => '',
        ],

        'mandatory' => [
            'AccountName' => env('AccountName', ''), //发信地址
            'AddressType' => env('AccessKeySecret  ', 0), //0-随机账号 1-发信地址
            'ReplyToAddress' => env('ReplyToAddress  ', 0), //回信地址
        ],

    ],



];
