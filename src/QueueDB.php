<?php

namespace Mrstock\Queue;

use Mrstock\Redis\RedisHelper;

/**
 * 队列处理
 *
 *
 * @package
 *
 */
class QueueDB
{

    // 定义对象
    private $handler;

    // 存储前缀
    private $_tb_prefix = '_TABLE';

    // 存定义存储表的数量,系统会随机分配存储 异步处理系统
    private $_tb_num = 1;

    /**
     * 初始化
     */
    public function __construct($host = "queue", $db = 3)
    {
        $this->handler = new RedisHelper($host, $db);
    }

    /**
     * 入列
     *
     * @param unknown $value
     */
    public function push($value, $prefix = "QUEUE")
    {

        $key = $prefix . $this->_tb_prefix . '_' . rand(1, $this->_tb_num);
        $key = strtoupper($key);

        return $this->handler->lPush($key, $value);
    }

    /**
     * 取得所有的list key(表)
     */
    public function scan($prefix = "QUEUE")
    {
        $list_key = array();
        for ($i = 1; $i <= $this->_tb_num; $i++) {
            $list_key[] = strtoupper($prefix . $this->_tb_prefix . '_' . $i);
        }
        return $list_key;
    }

    /**
     * 出列
     *
     * @param unknown $key
     */
    public function pop($key, $time)
    {
        $key = strtoupper($key);
        $count = $this->handler->LLEN($key);
        if ($count > 0) {
            $result = $this->handler->brPop($key, $time);
            if ($result) {
                return $result[1];
            }
        }
    }

    public function lindex($key, $index)
    {
        $result = $this->handler->LINDEX($key, $index);
        return $result;
    }
}