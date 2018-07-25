<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-21
 * Time: 下午9:08
 */

namespace QqBot\Storage;


interface StorageInterface
{
    public function getCookie($key);
    public function setCookie($key, $value);
    public function getCookieAll();

    public function getAuth($key);
    public function setAuth($key, $value);
    public function getAuthAll();

    public function setNickName($value);
    public function getNickName();

    public function clear();
}