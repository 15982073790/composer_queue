<?php
/**
 * Created by PhpStorm.
 * User: 李勇刚
 * Date: 2020/7/22
 * Time: 18:11
 */

namespace Mrstock\Queue\Test;


use Mrstock\Helper\Config;
use Mrstock\Queue\QueueDB;
use Mrstock\Redis\RedisHelper;
use PHPUnit\Framework\TestCase;

class QueuedbTest extends TestCase
{
    //检查host
    public function testPush()
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

        $QueueDB = new QueueDB('queue', 3);

        $res = $QueueDB->push('saddsdsa', 'queue_ceshi');

        //不为空
        $this->assertNotEmpty($res);
        //数字
        $this->assertIsInt($res);
    }

    //检查取得所有的list key(表)
    public function testScan()
    {

        $QueueDB = new QueueDB();

        $res = $QueueDB->scan('queue_ceshi');
        //不为空
        $this->assertNotEmpty($res);
        //数组
        $this->assertIsArray($res);
        //值
        $this->assertEquals($res[0], 'QUEUE_CESHI_TABLE_1');
    }

    //检查出列

    public function testPop()
    {
        $QueueDB = new QueueDB();

        $res = $QueueDB->pop('QUEUE_CESHI_TABLE_1', 0);
        if ($res) {
            //不为空
            $this->assertNotEmpty($res);
            //字符串
            $this->assertIsString($res);
            //数组
            $this->assertEquals($res, 'saddsdsa');
        } else {
            $this->assertEmpty($res);
        }
    }

    //检查lindex
    public function testLindex()
    {
        $QueueDB = new QueueDB();

        $res = $QueueDB->lindex('QUEUE_CESHI_TABLE_1', 0);

        if ($res) {
            //不为空
            $this->assertNotEmpty($res);
            //字符串
            $this->assertIsString($res);
            //数组
            $this->assertEquals($res, 'saddsdsa');
        } else {
            $this->assertEmpty($res);
        }
    }
}