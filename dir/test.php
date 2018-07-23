<?php

const COOKIE = __DIR__ . '/cookie.txt';
const COOKIE_XLOGIN = __DIR__ . '/cookie_xlogin.txt';
const COOKIE__QR = __DIR__ . '/cookie_qr.txt';
const COOKIE__LOGIN = __DIR__ . '/cookie_login.txt';
const AUTH_JSON = __DIR__ . '/auth.json';


const QR_FILE = __DIR__ . '/qr.png';

const CLIENT_ID = 53999199;

function xlogin()
{
    @unlink(COOKIE);
    @unlink(AUTH_JSON);
    $url = "https://xui.ptlogin2.qq.com/cgi-bin/xlogin?daid=164&target=self&style=40&pt_disable_pwd=1&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http://web2.qq.com/proxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    $res = curl_exec($ch);
    $infos = curl_getinfo($ch);

    if ($infos['http_code'] == 200) {
        file_put_contents(COOKIE, get_cookie_json($res));
        echo "xlogin cookie 写入成功" . PHP_EOL;
    }

    curl_close($ch);
}


function set_cookie_array($http_data)
{
    list($header, ) = explode("\r\n\r\n", $http_data);
    preg_match_all("/Set-Cookie: ([^\n\r]*)/i", $header, $matches);
    $cookies = $matches[1];
    $content = @file_get_contents(COOKIE) ?? [];
    $json_arr = is_array($content) ? [] : json_decode($content, true);
    foreach ($cookies as $cookie) {
        list($key, $value) = explode('=', explode(";", $cookie)[0]);
        if (empty($value)) continue;
        $json_arr[$key] = $value;
    }

    return $json_arr;
}

function get_cookie_json($http_data)
{
    return json_encode(set_cookie_array($http_data));
}

function get_cookie_array()
{
    return json_decode(file_get_contents(COOKIE), true);
}

function get_cookie_str()
{
    $content = get_cookie_array();

    $str = '';
    foreach ($content as $key => $value) {
        $str .= "$key=$value; ";
    }

    return trim($str, '; ');
}

function getQr()
{
    @unlink(QR_FILE);
    $url = "https://ssl.ptlogin2.qq.com/ptqrshow?appid=501004106&e=2&l=M&s=3&d=72&v=4&t=0.5053282138300335&daid=164&pt_3rd_aid=0";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    $res = curl_exec($ch);
    $infos = curl_getinfo($ch);

    if ($infos['http_code'] == 200) {
        list(, $body) = explode("\r\n\r\n", $res);
        file_put_contents(COOKIE, get_cookie_json($res));
        file_put_contents(QR_FILE, $body);
        echo "保存二维码成功，请登录" . PHP_EOL;
    } else {
        echo "获取二维码失败" . PHP_EOL;
    }
}

function bknHash($skey, $init_str = 5381)
{
    $hash_str = $init_str;
    $length = strlen($skey);

    for ($i = 0; $i < $length; ++$i) {
        $hash_str += ll($hash_str, 5) + ord($skey[$i]);
    }

    $hash_str = $hash_str & 2147483647;

    return $hash_str;
}

// 32 位运算
function ll($v, $n)
{
    $t = ($v & 0xFFFFFFFF) << ($n & 0x1F);
    return $t & 0x80000000 ? $t | 0xFFFFFFFF00000000 : $t & 0xFFFFFFFF;
}

function login()
{
    $cookies = get_cookie_array();
    $url = "https://ssl.ptlogin2.qq.com/ptqrlogin";
    $params = [
        'u1' => 'http://web2.qq.com/proxy.html',
        'ptqrtoken' => bknHash($cookies['qrsig'], 0),
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
    $url = $url . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_REFERER, "https://xui.ptlogin2.qq.com/cgi-bin/xlogin?daid=164&target=self&style=40&pt_disable_pwd=1&mibao_css=m_webqq&appid=501004106&enable_qlogin=0&no_verifyimg=1&s_url=http%3A%2F%2Fweb2.qq.com%2Fproxy.html&f_url=loginerroralert&strong_login=1&login_state=10&t=20131024001");
    $res = curl_exec($ch);
    $infos = curl_getinfo($ch);
    if ($infos['http_code'] == 200) {
        list(, $body) = explode("\r\n\r\n", $res);
        echo $body . PHP_EOL;
        if (preg_match('/二维码/', $body) == 0) {
            echo "登录成功" . PHP_EOL;
        }
        file_put_contents(COOKIE, get_cookie_json($res));
        preg_match("/(?<url>http[^\']*)/", $body, $match);
        return $match['url'];
    }
    curl_close($ch);
}

function get_ptwebqq() {
    $url = login();
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1");
    $res = curl_exec($ch);
    $infos = curl_getinfo($ch);

    if ($infos['http_code'] == 302) {
        echo $res;
        file_put_contents(COOKIE, get_cookie_json($res));
        echo "获取 ptwebqq 成功" . PHP_EOL;
    }
    curl_close($ch);
}

function getMillisecond() {
    list($s1, $s2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
}

function get_vfwebqq() {
    get_ptwebqq();
    $cookie = get_cookie_array();
    set_auth_json('ptwebqq', $cookie['ptwebqq'] ?? '');
    $url = "http://s.web2.qq.com/api/getvfwebqq";
    $params = [
        'ptwebqq' => $cookie['ptwebqq'] ?? '',
        'client_id' => CLIENT_ID,
        'psessionid' => '',
        't' => 0.1,
    ];
    $url = $url . '?' . http_build_query($params);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://s.web2.qq.com/proxy.html?v=20130916001&callback=1&id=1");
    $res = curl_exec($ch);
    $infos = curl_getinfo($ch);

    if ($infos['http_code'] == 200) {
        echo $res;
        $res = json_decode($res, true);
        if ($res["retcode"] == 0) {
            echo "获取 vfwebqq 成功" . PHP_EOL;
            set_auth_json('vfweqq', $res['result']['vfwebqq']);
            return $res['result']['vfwebqq'];
        }
    }
    curl_close($ch);
}

function set_auth_json($key, $value) {
    if (empty($value)) {
        return;
    }
    $content = get_auth_arr();
    $content[$key] = $value;
    file_put_contents(AUTH_JSON, json_encode($content));
}

function get_auth_arr() {
    $content = @file_get_contents(AUTH_JSON);
    return $content ? json_decode($content, true) : $content;
}

function get_psessionid_and_uin() {
    $url = "http://d1.web2.qq.com/channel/login2";
    $content = get_auth_arr();
    $params = "r=".json_encode([
            'ptwebqq' => $content['ptwebqq'] ?? '',
            'clientid' => CLIENT_ID,
            'psessionid' => '',
            'status' => 'online',
        ], JSON_FORCE_OBJECT);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2");
    $res = curl_exec($ch);
    $res = json_decode($res, true);
    if ($res['retcode'] == 0) {
        set_auth_json('psessionid', $res['result']['psessionid']);
        echo "登录成功!" . PHP_EOL;
    }
    curl_close($ch);
}

function poll() {
    $url = "http://d1.web2.qq.com/channel/poll2";

    $content = get_auth_arr();
    $params = "r=".json_encode([
            'ptwebqq' => $content['ptwebqq'] ?? '',
            'clientid' => CLIENT_ID,
            'psessionid' => $content['psessionid'],
            'key' => '',
        ], JSON_FORCE_OBJECT);

    $ch = curl_init($url);
    echo "poll: " . PHP_EOL;
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIE, get_cookie_str());
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36");
    //curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_REFERER, "http://d1.web2.qq.com/proxy.html?v=20151105001&callback=1&id=2");
    $res = curl_exec($ch);
    $res = json_decode($res, true);
    print_r($res);

    curl_close($ch);
}

switch ($argv[1]) {
    case 'qr':
        xlogin();
        getQr();
        break;
    case 'login':
        login();
        break;
    case 'ptwebqq':
        get_ptwebqq();
        break;
    case 'vfwebqq':
        get_vfwebqq();
        break;
    case 'psessionid':
        get_psessionid_and_uin();
        break;
    case 'poll':
        poll();
        break;
}

