<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/22
 * Time: 17:44
 */

namespace Mrstock\Queue\Test;


use Mrstock\Helper\Config;
use Mrstock\Queue\MQ;
use PHPUnit\Framework\TestCase;

class MqTest extends TestCase
{
    //检查host
    public function testHost()
    {
        $config['redis_config'] = array(

            'queue' => array(
                'prefix' => 'QUEUE_',
                'dynamicprefix' => ['site', 'appcode'],
                'type' => 'redis',
                'master' => array(array('host' => '192.168.10.231', 'port' => 6379, 'pconnect' => 0, 'db' => 3)),
                'slave' => array(array('host' => '192.168.10.231', 'port' => 6379, 'pconnect' => 0, 'db' => 3))
            ),
        );

        Config::set($config);

        $mq = new MQ();

        $res = $mq->host();

        //不为空
        $this->assertNotEmpty($res);
        //对象
        $this->assertIsObject($res);
    }

    //检查入列
    public function testPush()
    {
        $mq = new MQ();
        $data['stage'] = 'ceshi_queue';
        $data['id'] = 1;
        $data['name'] = 'hehe';
        $res = $mq->push($data);
        //不为空
        $this->assertNotEmpty($res);
        //int 数字;
        $this->assertIsInt($res);
    }

    //检查scan
    public function testScan()
    {
        $mq = new MQ();
        $res = $mq->scan('ceshi_queue');

        //不为空
        $this->assertNotEmpty($res);
        //数组
        $this->assertIsArray($res);
        //值
        $this->assertEquals($res[0], 'CESHI_QUEUE_TABLE_1');
    }

    //检查pop
    public function testPop()
    {
        $mq = new MQ();

        $res = $mq->pop('CESHI_QUEUE_TABLE_1');
        if ($res) {
            //不为空
            $this->assertNotEmpty($res);
            //字符串
            $this->assertIsString($res);
            $res = unserialize($res);
            //数组
            $this->assertIsArray($res);
        } else {
            $this->assertEmpty($res);
        }
    }

    //检查lindex
    public function testLindex()
    {
        $mq = new MQ();

        $res = $mq->lindex('CESHI_QUEUE_TABLE_1');
        if ($res) {
            //不为空
            $this->assertNotEmpty($res);
            //数组
            $this->assertIsArray($res);
        } else {
            $this->assertEmpty($res);
        }
    }
}