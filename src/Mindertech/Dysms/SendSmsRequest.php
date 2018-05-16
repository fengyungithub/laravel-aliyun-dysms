<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:34
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest as AliSendSmsRequest;
use Mindertech\Dysms\AcsClient;

/**
 * Class SendSmsRequest
 * @package Mindertech\Dysms
 */
class SendSmsRequest {

    /**
     * @var
     */
    public $app;

    /**
     * SendSmsRequest constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * @param $templateId
     * @param $sendTo
     * @param array $params
     * @param array $config
     * @param null $outId
     * @param null $extendCode
     * @param null $protocol
     * @return bool|\SimpleXMLElement|string
     * @throws \Exception
     */
    public function to($templateId, $sendTo, array $params = [], array $config = [], $outId = null, $extendCode = null, $protocol = null) {

        $client = new AcsClient($config);
        $request = new AliSendSmsRequest();
        $config = $client->getConfig();

        if(!is_null($protocol) && in_array($protocol, ['http', 'https'])) {
            $request->setProtocol($protocol);
        }

        $request->setPhoneNumbers($sendTo);
        $request->setSignName($config['sign']);
        $request->setTemplateCode($templateId);
        $request->setTemplateParam(json_encode($params, JSON_UNESCAPED_UNICODE));
        if(!is_null($outId)) {
            $request->setOutId($outId);
        }
        if(!is_null($extendCode)) {
            $request->setSmsUpExtendCode($extendCode);
        }

        $acsResponse = $client->getAcsClient()->getAcsResponse($request);

        /**
         * {"Message": "OK", "RequestId":"AC7CC92E-B0A0-4AB1-83DC-A42FBF757607", "BizId":"999612626451607776^0","Code": "OK"}
         */

        if(config('dysms.log')) {
            \Log::info(print_r($acsResponse, true));
        }

        $status = isset($acsResponse->Code) ? $acsResponse->Code : 'ERROR';
        $bizId = isset($acsResponse->BizId) ? $acsResponse->BizId : '';

        if(strtolower($status) !== 'ok') {
            throw new \Exception($acsResponse->Code);
        }

        return $bizId;

    }
}