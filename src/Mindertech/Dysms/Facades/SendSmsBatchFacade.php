<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:50
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms\Facades;

use Illuminate\Support\Facades\Facade;
class SendSmsBatchFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'send-sms-batch';
    }
}