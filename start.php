<?php


use QqBot\Bot;
use QqBot\Extension\TuLing;

require_once __DIR__ . '/vendor/autoload.php';
//use QqBot\QqBotApi;
//$qq = new QqBotApi();
//$data = $qq->poll2();

$bot = new Bot();
//$message = $bot->getMessage();
//print_r($message);

$bot->getFriends();exit;
//$bot->sendFriendMessage('1771972612', '你好');
//$bot->sendGroupMessage('1916166069', '测试群消息');

while (true) {
    $message = $bot->getMessage();
    if ($message->send_uin == $message->to_uin) {
        echo "我说: " . $message->poll_type . ": " . $message->send_uin . ": " .$message->content . PHP_EOL;
    } else {
        echo $message->poll_type . ": " . $message->send_uin . ": " .$message->content . PHP_EOL;
    }
    if ($message->from_uin == '2111051074' && $message->send_uin != '1203375063') {
        $res = TuLing::request($message->content);

        foreach ($res as $key => $item) {
            $bot->sendDiscuMessage('2111051074', $item);
        }
    }
}

