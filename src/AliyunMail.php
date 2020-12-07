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
                $curl->get($this->config['api_url'], array_merge($public, $args));
                break;

            case self::DESC_ACCOUNT_SUMMARY:
                $curl->get($this->config['api_url'], $public);
        }


        if ($curl->error) {
            throw new \Exception('Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . ':'.json_encode($curl->response)."\n");
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
            'Action' => $this->config['type'],
            'RegionId' => $area['RegionId'],
            'Version' => $area['Version'],
        ]);

        $paramString = '';
        ksort($params);
        foreach ($params as $k => $v) {
            $paramString .= $this->percentEncode($k). '=' . $this->percentEncode($v).'&';
        }

        $apiKey = $this->config->get('auth')['AccessKeySecret'];
        $params['Signature'] = base64_encode(hash_hmac("sha1", $httpMethod . '&%2F&' . $this->percentencode(rtrim($paramString, '&')), $apiKey.'&', true));

        return $params;
    }

    protected function percentEncode($str)
    {
        $res = urlencode($str);
        $res = preg_replace('/\+/', '%20', $res);
        $res = preg_replace('/\*/', '%2A', $res);
        $res = preg_replace('/%7E/', '~', $res);
        return $res;
    }


}