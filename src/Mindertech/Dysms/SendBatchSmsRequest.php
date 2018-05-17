<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:35
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use Aliyun\Api\Sms\Request\V20170525\SendBatchSmsRequest as AliyunSendBatchSmsRequest;
use Mindertech\Dysms\Traits\RuntimeConfig;

/**
 * Class SendBatchSmsRequest
 * @package Mindertech\Dysms
 */
class SendBatchSmsRequest {

    use RuntimeConfig;
    /**
     * @var
     */
    public $app;

    /**
     * SendBatchSmsRequest constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * @param $templateId
     * @param array $phoneNumbers
     * @param array $sign
     * @param array $params
     * @param array|null $extendCodes
     * @param null $protocol
     * @return \SimpleXMLElement|string
     * @throws \Exception
     */
    public function to($templateId, array $phoneNumbers, array $sign, array $params = [], array $extendCodes = null, $protocol = null)
    {

        $client = new AcsClient($this->getRuntimeConfig());

        $request = new AliyunSendBatchSmsRequest();
        $request->setPhoneNumberJson(json_encode($phoneNumbers, JSON_UNESCAPED_UNICODE));
        $request->setSignNameJson(json_encode($sign, JSON_UNESCAPED_UNICODE));
        $request->setTemplateCode($templateId);
        $request->setTemplateParamJson(json_encode($params, JSON_UNESCAPED_UNICODE));
        if(!is_null($protocol)) {
            $request->setProtocol($protocol);
        }
        if(!is_null($extendCodes)) {
            $request->setSmsUpExtendCodeJson(json_encode($extendCodes, JSON_UNESCAPED_UNICODE));
        }

        $acsResponse = $client->getAcsClient()->getAcsResponse($request);

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