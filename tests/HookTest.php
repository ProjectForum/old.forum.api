<?php

use PHPUnit\Framework\TestCase;
use forum\library\Hook;

class HookTest extends TestCase
{
    /**
     * 测试钩子的监听和触发
     *
     * @return void
     */
    public function testHookListenAndTrigger()
    {
        $hook = new Hook();
        $mock = $this->getMockBuilder('stdClass')
            ->setMethods(['callback'])
            ->getMock();

        $mock->expects($this->once())
            ->method('callback')
            ->willReturn(true);

        $hook->listen('unittest_event', 'ute', [$mock, 'callback']);
        $hook->trigger('unittest_event');
    }
}
