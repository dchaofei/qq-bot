<?php


namespace QqBot;


use QqBot\Contact\Friends;
use QqBot\Contact\Groups;

class Bot
{
    public static $qq;

    public $qq_instance;

    /** @var QqBotApi */
    static $bot;

    public function __construct()
    {
        $this->qq_instance = new QqBotApi();
        static::$bot = $this->qq_instance;
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

    /**
     * @return Friends[]
     */
    public function getFriends()
    {
        return $this->qq_instance->getFriends();
    }

    /**
     * @return Groups[]
     */
    public function getGroups()
    {
        return $this->qq_instance->getGroups();
    }

    public function getDiscus()
    {
        return $this->qq_instance->getDiscus();
    }
}