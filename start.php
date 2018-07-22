<?php


require_once __DIR__ . '/vendor/autoload.php';
use QqBot\QqBotApi;
$qq = new QqBotApi();
$data = $qq->poll2();
print_r($data);