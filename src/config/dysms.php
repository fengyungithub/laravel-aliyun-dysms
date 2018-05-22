<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:24
 * @author: GROOT (pzyme@outlook.com)
 */

return [
    'access_key_id' => '',
    'access_key_secret' => '',
    'sign' => '',
    'log' => false,
    'sms-report-queue' => '',
    'sms-up-queue' => '',

    //以下配置暂时无需替换
    'product' => 'Dysmsapi',
    'domain' => 'dysmsapi.aliyuncs.com',
    'region' => 'cn-hangzhou',
    'end_point_name' => 'cn-hangzhou',
    'mns' => [
        'account_id' => '1943695596114318',
        'product' => 'Dybaseapi',
        'domain' => 'dybaseapi.aliyuncs.com',
        'wait_seconds' => 3
    ],
];