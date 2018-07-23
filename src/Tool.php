<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-21
 * Time: 下午10:34
 */

namespace QqBot;


class Tool
{
    public static function bknHash($skey, $init_str = 5381)
    {
        $hash_str = $init_str;
        $length = strlen($skey);

        for ($i = 0; $i < $length; ++$i) {
            $hash_str += static::leftShift($hash_str, 5) + ord($skey[$i]);
        }

        $hash_str = $hash_str & 2147483647;

        return $hash_str;
    }

    // x86 左位移运算
    public static function leftShift($v, $n)
    {
        $t = ($v & 0xFFFFFFFF) << ($n & 0x1F);
        return $t & 0x80000000 ? $t | 0xFFFFFFFF00000000 : $t & 0xFFFFFFFF;
    }

    public static function hash($uin, $ptwebqq)
    {
        // TODO 待实现
    }
}