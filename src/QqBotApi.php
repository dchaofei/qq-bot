<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-21
 * Time: 下午8:58
 */

namespace QqBot;


use QqBot\Message\Message;
use QqBot\Storage\Redis;
use QqBot\Storage\StorageInterface;

class QqBotApi
{
    const XLOGIN = "https://xui.ptlogin2.qq.com/cgi-bin/xlogin?daid=164&target=self&style=40&pt_disable_pwd=1&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http://web2.qq.com/proxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001";
    const QR = "https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=2&l=M&s=3&d=72&v=4&t=0.5053282138300335&daid=164&pt_3rd_aid=0";
    const LOGIN = "https://ssl.ptlogin2.qq.com/ptqrlogin";
    const VF_WEBQQ = "http://s.web2.qq.com/api/getvfwebqq";
    const LOGIN2 = "http://d1.web2.qq.com/channel/login2";

    // 获取消息接口
    const POLL = "http://d1.web2.qq.com/channel/poll2";

    const CLIENT_ID = 53999199;

    /** @var $storage StorageInterface */
    public static $storage;

    public function __construct()
    {
        static::$storage = new Redis();
        $this->init();
    }

    public function init()
    {
        // 这四步为登录
        $this->xLogin();
        $this->showQr();
        $this->getVfWebqq();
        $this->getPsessionidAndUin();
    }

    public function xLogin()
    {
        $res = Curl::get(self::XLOGIN);

        if (!$res) {
            echo "模拟打开登录页面成功" . PHP_EOL;
        }
    }

    public function showQr()
    {
        $file_name = __DIR__ . '/qr.png';
        @unlink($file_name);

        $res = Curl::get(self::QR, [
            CURLOPT_COOKIE => $this->buildCookie(),
        ]);

        if ($res) {
            file_put_contents($file_name, $res);
            echo "请打开二维码扫码登录, $file_name" . PHP_EOL;
        } else {
            die('获取登录二维码失败');
        }
    }

    public function getPtwebqqUrl()
    {
        $params = [
            'u1' => 'http://web2.qq.com/proxy.html',
            'ptqrtoken' => Tool::bknHash(static::$storage->getCookie('qrsig'), 0),
            'ptredirect' => '0',
            'h' => '1',
            't' => '1',
            'g' => '1',
            'from_ui' => '1',
            'ptlang' => '2052',
            'action' => '1-0-1532142989455',
            'js_ver' => '10276',
            'js_type' => '1',
            //'login_sig' => 'c7LY6*fk2GW-R40BizPrWLRHN0a8xPxx9YZOXgiEL0opf3Pd2jfkEijXPVslRIOW',
            'pt_uistyle' => '40',
            'aid' => '501004106',
            'daid' => '164',
            'mibao_css' => 'm_webqq',
        ];
        $url = self::LOGIN . '?' . http_build_query($params);

        $res = Curl::get($url, [
            CURLOPT_COOKIE => $this->buildCookie(),
            CURLOPT_REFERER => 'https://xui.ptlogin2.qq.com/cgi-bin/xlogin?daid=164&target=self&style=40&pt_disable_pwd=1&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fweb2.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001',
        ]);

        if ($res) {
            preg_match("/(?<url>http[^\']*)/", $res, $match);
            if ($match) {
                echo "获取 PtwebqqUrl 成功" . PHP_EOL;
                return $match['url'];
            } else if (preg_match('/未失效/', $res) || preg_match('/认证中/', $res)) {
                sleep(2);
                return $this->getPtwebqqUrl();
            }
        }
        echo $res . PHP_EOL;
        die('获取 PtwebqqURl 失败');
    }

    public function getPtWebQQ()
    {
        //TODO 设置 ptwebqq auth
        $url = $this->getPtwebqqUrl();
        Curl::get($url, [
            CURLOPT_COOKIE => $this->buildCookie(),
            CURLOPT_REFERER => 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1',
        ]);
    }

    public function getVfWebqq()
    {
        $this->getPtWebQQ();
        $params = [
            'ptwebqq' => static::$storage->getCookie('ptwebqq') ?? '',
            'client_id' => self::CLIENT_ID,
            'psessionid' => '',
            't' => 0.1,
        ];
        $url = self::VF_WEBQQ. '?' . http_build_query($params);

        $res = Curl::get($url, [
            CURLOPT_COOKIE => $this->buildCookie(),
            CURLOPT_REFERER => 'http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1',
        ]);

        $res = json_decode($res, true);

        if ($res["retcode"] == 0) {
            echo "获取 vfwebqq 成功" . PHP_EOL;
            static::$storage->setAuth('vfweqq', $res['result']['vfwebqq']);
        } else {
            die("获取 vfwebqq 失败");
        }
    }

    public function getPsessionidAndUin()
    {
        $params = "r=".json_encode([
                'ptwebqq' => static::$storage->getCookie('ptwebqq') ?? '',
                'clientid' => self::CLIENT_ID,
                'psessionid' => '',
                'status' => 'online',
            ], JSON_FORCE_OBJECT);

        $res = Curl::post(self::LOGIN2, [
            CURLOPT_COOKIE => $this->buildCookie(),
            CURLOPT_REFERER => 'http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2',
            CURLOPT_POSTFIELDS => $params
        ]);

        $res = json_decode($res, true);

        if ($res['retcode'] == 0) {
            static::$storage->setAuth('psessionid', $res['result']['psessionid']);
            static::$storage->setAuth('psessionid', $res['result']['uin']);
            echo "登录成功!" . PHP_EOL;
        } else {
            die('登录失败');
        }
    }


    public function poll2()
    {
        $params = "r=".json_encode([
                'ptwebqq' => static::$storage->getCookie('ptwebqq') ?? '',
                'clientid' => self::CLIENT_ID,
                'psessionid' => '',
                'key' => '',
            ], JSON_FORCE_OBJECT);

        $res = Curl::post(self::POLL, [
            CURLOPT_COOKIE => $this->buildCookie(),
            CURLOPT_REFERER => "http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2",
            CURLOPT_POSTFIELDS => $params
        ]);
        $res = json_decode($res, true);
        print_r($res);
        if ($res['retcode'] == 0 && $res['errmsg'] != 'error') {
            return $this->formatResponse($res['result']);
        } else {
            echo "获取失败";
            print_r($res);
            $this->poll2();
        }
    }

    protected function formatResponse($result)
    {
        switch ($result['poll_type']) {
            case 'message':
                return new Message($result);
        }
    }

    protected function buildCookie()
    {
        $cookies = static::$storage->getCookieAll();

        $str = '';
        foreach ($cookies as $key => $value) {
            $str .= "$key=$value; ";
        }

        return trim($str, '; ');
    }
}