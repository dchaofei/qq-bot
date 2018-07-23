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
        $n   = [0, 0, 0, 0];
        $len = strlen($ptwebqq);
        echo "len: $len".PHP_EOL;
        for ($i = 0; $i < $len; $i++) {
            $n[$i % 4] ^= ord($ptwebqq{$i});
        }
        $u    = ["EC", "OK"];
        $v    = [];
        $v[0] = $uin >> 24 & 255 ^ ord($u['0']['0']);
        $v[1] = $uin >> 16 & 255 ^ ord($u['0']['1']);
        $v[2] = $uin >> 8 & 255 ^ ord($u['1']['0']);
        $v[3] = $uin & 255 ^ ord($u['1']['1']);
        $N    = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F"];
        $V    = "";
        for ($i = 0; $i < 8; $i++) {
            $t = $i % 2 == 0 ? $n[$i >> 1] : $v[$i >> 1];
            $V .= $N[$t >> 4 & 15];
            $V .= $N[$t & 15];
        }
        return $V;
    }
}