<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:35
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest as AliyunQuerySendDetailsRequest;

/**
 * Class QuerySendDetailsRequest
 * @package Mindertech\Dysms
 */
class QuerySendDetailsRequest {

    /**
     * @var
     */
    public $app;

    /**
     * QuerySendDetailsRequest constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }


    /**
     * @param $phoneNumber
     * @param $sendDate
     * @param array $config
     * @param int $page
     * @param int $pageSize
     * @param null $bizId
     * @param null $protocol
     * @return array
     */
    public function search($phoneNumber, $sendDate, array $config = [], $page = 1, $pageSize = 10, $bizId = null, $protocol = null)
    {
        $client = new AcsClient($config);
        $config = $client->getConfig();

        $request = new AliyunQuerySendDetailsRequest();
        $request->setPhoneNumber($phoneNumber);
        $request->setSendDate($sendDate);
        $request->setPageSize($pageSize);
        $request->setCurrentPage($page);

        if(!is_null($bizId)) {
            $request->setBizId($bizId);
        }
        if(!is_null($protocol)) {
            $request->setProtocol($protocol);
        }

        $acsResponse = $client->getAcsClient()->getAcsResponse($request);

        $status = isset($acsResponse->Code) ? $acsResponse->Code : 'ERROR';

        $default = [
            'page' => $page,
            'pageSize' => $pageSize,
            'sendDate' => $sendDate,
            'result' => []
        ];

        if(strtolower($status) !== 'ok') {
            return $default;
        }

        return array_merge($default, [
            'total' => $acsResponse->TotalCount,
            'result' => $acsResponse->SmsSendDetailDTOs->SmsSendDetailDTO
        ]);
    }
}