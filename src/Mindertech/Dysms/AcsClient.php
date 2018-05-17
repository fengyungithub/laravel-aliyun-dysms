<?php
/**
 * laravel-aliyun-dysms
 * Date: 2018-05-16 10:55
 * @author: GROOT (pzyme@outlook.com)
 */

namespace Mindertech\Dysms;

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;

/**
 * Class AcsClient
 * @package Mindertech\Dysms
 */
class AcsClient
{

    /**
     * @var array
     */
    private $config = [];


    /**
     * AcsClient constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $defaultConfig = config('dysms');

        $this->config = array_merge($defaultConfig, $config);
    }

    /**
     * @return DefaultAcsClient
     */
    public function getAcsClient()
    {
        Config::load();
        $profile = DefaultProfile::getProfile(
            $this->config['region'],
            $this->config['access_key_id'],
            $this->config['access_key_secret']
        );
        DefaultProfile::addEndpoint(
            $this->config['end_point_name'],
            $this->config['region'],
            $this->config['product'],
            $this->config['domain']
        );

        return new DefaultAcsClient($profile);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

}