<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/27
 * Time: 9:37
 */

namespace Mrstock\Queue\Test;


use Mrstock\Helper\Config;
use Mrstock\Queue\QueueServer;
use PHPUnit\Framework\TestCase;

class QueueserverTest extends TestCase
{
    //检查HOST方法
    public function testHost()
    {
        if (!Config::get('redis_config')) {
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
        }
        $queueserver = new QueueServer();

        $res = $queueserver->host('queue', 3);

        //不为空
        $this->assertNotEmpty($res);
        //对象
        $this->assertIsObject($res);
    }

    //检查取出队列
    public function testPop()
    {
        $queueserver = new QueueServer();

        $res = $queueserver->pop('QUEUE_CESHI_TABLE_1');

        //为空
        $this->assertEmpty($res);
        //断言为bool值
        $this->assertIsBool($res);
        //断言值的范围
        $this->assertContains($res, [false, null, 0]);
    }

    //检查scan方法
    public function testScan()
    {
        $queueserver = new QueueServer();

        $res = $queueserver->scan();

        //不为空
        $this->assertNotEmpty($res);
        //断言为数组
        $this->assertIsArray($res);
        //断言值
        $this->assertEquals($res[0], 'QUEUE_TABLE_1');
    }

    //检查lindex
    public function testLindex()
    {
        $queueserver = new QueueServer();

        $res = $queueserver->lindex('QUEUE_TABLE_1');

        //为空
        $this->assertEmpty($res);
        //断言为bool值
        $this->assertIsBool($res);
        //断言值的范围
        $this->assertContains($res, [false, null, 0]);
    }
}