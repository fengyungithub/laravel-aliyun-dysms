<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:35
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

class QuerySendDetailsRequest {
    public $app;

    public function __construct($app)
    {
        $this->app = $app;
    }
}