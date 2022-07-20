<?php

namespace Mrstock\Queue;

use Mrstock\Mjc\Facade\Log;
use Mrstock\Mjc\Log\LogLevel;

class QueueServer
{

    protected $queuedb;

    protected $prefix = 'QUEUE';

    public function __construct($key = '', $host = "queue", $db = 3)
    {
        if (!empty($key)) {
            $this->prefix = $key;
        }
        $this->queuedb = new QueueDB($host, $db);
    }

    public function host($host = "queue", $db = 3)
    {
        $this->queuedb = new QueueDB($host, $db);
        return $this;
    }

    /**
     * 取出队列
     *
     * @param unknown $key
     */
    public function pop($key, $time = 0, $isserialize = 0)
    {
        if ($isserialize == 1) {
            return $this->queuedb->pop($key, $time);
        } else {
            $result = $this->queuedb->pop($key, $time);
            if (!empty($result) && empty(unserialize($result))) {
                Log::record("pop_unserialize " . $result, LogLevel::FILERECORD);
            }

            return unserialize($result);
        }
    }

    public function scan()
    {
        return $this->queuedb->scan($this->prefix);
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