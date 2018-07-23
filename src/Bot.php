<?php


namespace QqBot;


class Bot
{
    public static $qq;

    public $qq_instance;

    public function __construct()
    {
        $this->qq_instance = new QqBotApi();
    }

    public static function getNickName()
    {
        return QqBotApi::$storage->getNickName();
    }

    public function login()
    {
        $this->qq_instance->qqlogin();
    }

    public function getMessage()
    {
        return $this->qq_instance->poll2();
    }

    public function sendFriendMessage($to_id, $content)
    {
        $this->qq_instance->sendFriendMessage($to_id, $content);
    }

    public function sendGroupMessage($to_id, $content)
    {
        $this->qq_instance->sendGroupMessage($to_id, $content);
    }

    public function sendDiscuMessage($to_id, $content)
    {
        $this->qq_instance->sendDiscuMessage($to_id, $content);
    }

    public function getFriends()
    {
        $this->qq_instance->getFriends();
    }
}