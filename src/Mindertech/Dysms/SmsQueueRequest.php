<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:35
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use AliyunMNS\Lib\TokenGetterForAlicom;
use AliyunMNS\Lib\TokenForAlicom;
use Aliyun\Core\Config;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\BatchReceiveMessageRequest;
use Mindertech\Dysms\Traits\RuntimeConfig;

/**
 * Class SmsQueueRequest
 * @package Mindertech\Dysms
 */
class SmsQueueRequest
{

    /**
     *
     */
    use RuntimeConfig;

    /**
     * @var null
     */
    private $tokenGetter = null;
    /**
     * @var array
     */
    private $config = [];
    /**
     * @var
     */
    public $app;

    /**
     * SmsQueueRequest constructor.
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
        $this->mergeConfig();
    }

    /**
     *
     */
    private function mergeConfig()
    {
        $this->config = array_merge(config('dysms'), $this->getRuntimeConfig());
    }

    /**
     * @return TokenGetterForAlicom|null
     */
    private function getTokenGetter()
    {
        $this->tokenGetter = new TokenGetterForAlicom($this->config);

        return $this->tokenGetter;
    }


    /**
     * @param callable $callback
     */
    public function up(callable $callback)
    {
        $this->mergeConfig();
        $this->receiveMessage('SmsUp', $this->config['sms-up-queue'], $callback);
    }


    /**
     * @param callable $callback
     */
    public function report(callable $callback)
    {
        $this->mergeConfig();
        $this->receiveMessage('SmsReport', $this->config['sms-report-queue'], $callback);
    }


    /**
     * @param $messageType
     * @param $queueName
     * @param callable $callback
     * @throws
     */
    private function receiveMessage($messageType, $queueName, callable $callback)
    {
        $i = 0;
        // 取回执消息失败3次则停止循环拉取
        while ($i < 3) {
            try {
                // 取临时token
                $tokenForAlicom = $this->getTokenGetter()->getTokenByMessageType($messageType, $queueName);

                // 使用MNSClient得到Queue
                $queue = $tokenForAlicom->getClient()->getQueueRef($queueName);

                // ------------------------------------------------------------------
                // 1. 单次接收消息，并根据实际情况设置超时时间
                $message = $queue->receiveMessage(2);

                // 计算消息体的摘要用作校验
                $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                // 比对摘要，防止消息被截断或发生错误
                if ($bodyMD5 == $message->getMessageBodyMD5()) {
                    // 执行回调
                    if (call_user_func($callback, json_decode($message->getMessageBody()))) {
                        // 当回调返回真值时，删除已接收的信息
                        $receiptHandle = $message->getReceiptHandle();
                        $queue->deleteMessage($receiptHandle);
                    }
                }
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // 2. 批量接收消息
                // $res = $queue->batchReceiveMessage(new BatchReceiveMessageRequest(10, 5)); // 每次拉取10条，超时等待时间5秒

                // /* @var \AliyunMNS\Model\Message[] $messages */
                // $messages = $res->getMessages();

                // foreach($messages as $message) {
                //     // 计算消息体的摘要用作校验
                //     $bodyMD5 = strtoupper(md5(base64_encode($message->getMessageBody())));

                //     // 比对摘要，防止消息被截断或发生错误
                //     if ($bodyMD5 == $message->getMessageBodyMD5())
                //     {
                //         // 执行回调
                //         if(call_user_func($callback, json_decode($message->getMessageBody())))
                //         {
                //             // 当回调返回真值时，删除已接收的信息
                //             $receiptHandle = $message->getReceiptHandle();
                //             $queue->deleteMessage($receiptHandle);
                //         }
                //     }
                // }
                // ------------------------------------------------------------------

                return; // 整个取回执消息流程完成后退出
            } catch (MnsException $e) {
                $i++;
                if ($this->config['log']) {
                    \Log::info("ex:{$e->getMnsErrorCode()}");
                    \Log::info("ReceiveMessage Failed: {$e}");
                }
            }
        }
    }
}