<?php

use PHPUnit\Framework\TestCase;
use forum\user\Session;
use forum\user\Secret;

class UserTest extends TestCase
{
    /**
     * 测试 Json Web Token 的创建与解析
     *
     * @return void
     */
    public function testJWTCreateAndParse()
    {
        $secret = new Secret;
        $session = new Session($secret->getSecretKey());

        $originPayload = [
            'time' => time(),
            'extra' => 'test',
        ];
        $token = $session->createToken($originPayload);
        $parsedTime = $session->load($token)->get('time');
        $this->assertEquals($originPayload['time'], $parsedTime);
    }
}
