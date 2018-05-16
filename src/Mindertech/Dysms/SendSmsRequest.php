<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:34
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest as AliSendSmsRequest;
use Mindertech\Dysms\AcsClient;

class SendSmsRequest {

    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }


    public function send($templateId, $sendTo, array $params = [], array $config = [], $outId = null, $extendCode = null, $protocol = null) : bool {

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

        $acsResponse = $client->getAcsResponse($request);

        $status = array_get($acsResponse, 'code');

        return strtolower($status) === 'ok' ? true : $acsResponse;

    }
}