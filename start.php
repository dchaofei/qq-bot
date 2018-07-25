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


//while (true) {
//    $message = $bot->getMessage();
//    if ($message->send_uin == $message->to_uin) {
//        echo "我说: " . $message->poll_type . ": " . $message->send_uin . ": " .$message->content . PHP_EOL;
//    } else {
//        echo $message->poll_type . ": " . $message->send_uin . ": " .$message->content . PHP_EOL;
//    }
//    if ($message->from_uin == '693173041' && $message->send_uin != '1203375063') {
//        $res = TuLing::request($message->content);
//
//        foreach ($res as $key => $item) {
//            $bot->sendGroupMessage('693173041', $item);
//        }
//    }
//}

while (true) {
    $message = $bot->getMessage();
    echo $message->poll_type . ": " . $message->send_uin . ": " .$message->content . PHP_EOL;
}

