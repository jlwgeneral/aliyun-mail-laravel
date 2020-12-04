<?php

namespace JlwGeneral\AliyunMailLaravel;

use Curl\Curl;
use JlwGeneral\AliyunMailLaravel\Contracts\AliyunMail\Factory;

class AliyunMail implements Factory
{

    /**
     * @var \JlwGeneral\AliyunMailLaravel\Config
     */
    protected $config;
    protected $url;

    const SINGLE_SEND_MAIL = 'SingleSendMail';
    const BATCH_SEND_MAIL = 'BatchSendMail';
    const DESC_ACCOUNT_SUMMARY = 'DescAccountSummary';
    public static $TYPE = [
        self::SINGLE_SEND_MAIL,
    ];

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function send(array $args)
    {
        $public = $this->generateSignature();
        $curl = new Curl();

        switch ($this->config['type']) {
            case self::SINGLE_SEND_MAIL:
            case self::BATCH_SEND_MAIL:
                $mandatory = empty($args['mandatory']) ? $this->config->get('public.mandatory') : $args['mandatory'];
                unset($args['mandatory']);
                array_merge($args, $mandatory);
                $curl->get($this->config['api_url'] . $this->config['type'], array_merge($public, $args));
                break;

            case self::DESC_ACCOUNT_SUMMARY:
                $curl->get($this->config['api_url'] . $this->config['type']);
        }


        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n");
        }
        return $curl->response;
    }

    public function setCity($city)
    {
        $this->config['city'] = $city;
    }

    public function setType($type)
    {
        $this->config['type'] = $type;
    }


    public function generateSignature($httpMethod = 'GET')
    {

        $area = $this->config->get("area.{$this->config['city']}");
        $params = array_merge($this->config->get('public.params'), [
            'AccessKeyId' => $area['AccessKeyId'],
            'Version' => $area['Version'],
        ]);

        $paramString = '';
        foreach (ksort($params) as $k => $v) {
            $paramString .= "$k=$v&";
        }
        $stringToSign = $httpMethod . '&' . urlencode('/') . '&' . urlencode(rtrim($paramString, '&'));

        $apiKey = $this->config->get('auth.AccessKeySecret'){'&'};
        $params['Signature'] = base64_encode(hash_hmac("sha1", $stringToSign, $apiKey, true));

        return $params;
    }


}