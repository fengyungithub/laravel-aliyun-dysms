<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-17 10:04
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms\Traits;

/**
 * Trait SetConfig
 * @package Mindertech\Dysms\Traits
 */
trait RuntimeConfig
{

    /**
     * @var
     */
    private $runtimeConfig = [];


    /**
     * @param array $config
     * @return $this
     */
    public function setRuntimeConfig(array $config = [])
    {
        $this->runtimeConfig = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRuntimeConfig()
    {
        return $this->runtimeConfig;
    }
}