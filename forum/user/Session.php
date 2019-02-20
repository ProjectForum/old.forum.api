<?php
namespace forum\user;

use \Firebase\JWT\JWT;

class Session
{
    protected $_secretKey;
    protected $_payload;

    public function __construct(string $secretKey)
    {
        $this->_secretKey = $secretKey;
    }

    /**
     * 创建 Json Web Token
     *
     * @param array $payload
     * @return string
     */
    public function createToken(array $payload) : string
    {
        return JWT::encode($payload, $this->_secretKey);
    }

    /**
     * 加载 payload
     *
     * @param string $jsonWebToken
     * @return Session
     */
    public function load(string $jsonWebToken) : Session
    {
        try {
            $this->_payload = JWT::decode($jsonWebToken, $this->_secretKey, ['HS256']);
        } catch (\Exception $e) {
            $this->_payload = null;
        }
    }

    /**
     * 通过是否加载 payload 来判断是否登录
     *
     * @return boolean
     */
    public function hasLogged() : bool
    {
        return !empty($this->_payload);
    }

    /**
     * 从会话 payload 中读取字段
     *
     * @param string $field 字段名
     * @param mixed $default 默认值
     * @return mixed
     */
    public function get(string $field, $default = null)
    {
        if (!$this->hasLogged()) {
            return $default;
        }

        if (property_exists($this->_payload, $field)) {
            return $this->_payload->{$field};
        }

        return $default;
    }
}
