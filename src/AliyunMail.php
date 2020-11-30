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

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    public function send(array $params)
    {


        switch ($this->config['type']) {
            case 'SingleSendMail':
                if (!isset($params['send'])) {
                    $params['send'] = $this->config->get('public.send');
                }
                try{
                    $curl = new Curl();
                }catch (\Exception $e) {

                }
                break;
        }
        return 'ok';
    }

    public function setCity($city)
    {
        $this->config['city'] = $city;
    }

    public function setType($type)
    {
        $this->config['type'] = $type;
    }


    public function generateSignature($city, $httpMethod = 'GET')
    {

        $area = $this->config->get("area.{$city}");
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
        return base64_encode(hash_hmac("sha1", $stringToSign, $apiKey, true));
    }

}