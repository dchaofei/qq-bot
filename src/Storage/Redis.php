<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-21
 * Time: 下午9:12
 */

namespace QqBot\Storage;


class Redis implements StorageInterface
{
    public $redis;

    /** @var array */
    private $cookies = [];

    /** @var array */
    private $auths;

    private $_cookieName = 'QQBOT:COOKIE';
    private $_authName = 'QQBOT:AUTH';

    public function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1');
        $this->clear();
    }

    public function setPreFix($value)
    {
        $this->_prefix = $value;
    }

    private function get($key, $type)
    {
        if (array_key_exists($key, $this->cookies)) {
            $ret = $this->cookies[$key];
        } else {
            if ($type == 'cookie') {
                $table = $this->_cookieName;
            } else {
                $table = $this->_authName;
            }
            $ret = $this->redis->hGet($table, $key);
        }
        return $ret;
    }

    private function set($key, $value, $type)
    {
        if (empty($value)) {
            return;
        }

        $this->cookies[$key] = $value;

        if ($type == 'cookie') {
            $table = $this->_cookieName;
        } else {
            $table = $this->_authName;
        }

        $this->redis->hSet($table, $key, $value);
    }

    public function getCookie($key)
    {
        return $this->get($key, 'cookie');
    }

    public function setCookie($key, $value)
    {
        $this->set($key, $value, 'cookie');
    }

    public function getCookieAll()
    {
        if (!empty($this->cookies)) {
            return $this->cookies;
        }

        return $this->redis->hGetAll($this->_cookieName);
    }

    public function getAuth($key)
    {
        return $this->get($key, 'auth');
    }

    public function setAuth($key, $value)
    {
        return $this->set($key, $value, 'auth');
    }

    public function getAuthAll()
    {
        if (!empty($this->auths)) {
            return $this->auths;
        }

        return $this->redis->hGetAll($this->_authName);
    }

    public function clear()
    {
        $this->redis->hDel($this->_authName);
        $this->redis->hDel($this->_cookieName);
    }

}