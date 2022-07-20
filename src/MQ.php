<?php

namespace Mrstock\Queue;

/**
 * 队列处理
 *
 *
 * @package
 *
 */
class MQ
{

    protected $queuedb;

    protected $prefix = 'QUEUE';

    public function __construct($host = "queue", $db = 3)
    {
        $this->queuedb = new QueueDB($host, $db);
    }

    public function host($host = "queue", $db = 3)
    {
        $this->queuedb = new QueueDB($host, $db);
        return $this;
    }

    /**
     * 入列
     *
     * @param string $key
     * @param array $value
     */
    public function push($value)
    {
        $stage = $this->prefix;
        if (isset($value['stage'])) {
            $stage = $value['stage'];
        }

        return $this->queuedb->push(serialize($value), $stage);
    }

    public function scan($key)
    {

        return $this->queuedb->scan($key);
    }

    public function pop($key, $time = 0)
    {

        return $this->queuedb->pop($key, $time = 0);
    }

    public function lindex($key, $index = 0, $unserialize = 0)
    {
        $result = $this->queuedb->lindex($key, $index);
        if (empty($unserialize)) {
            $result = unserialize($result);
        }

        return $result;
    }
}

