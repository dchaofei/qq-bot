<?php


namespace QqBot\MessageSource;


class MessageAbstract
{
    const FRIEND = 'message';
    const GROUP = 'group_message';
    const DISCU = 'discu_message';

    public $poll_type;

    public function isFriend()
    {
        return $this->poll_type == self::FRIEND;
    }

    public function isGroup()
    {
        return $this->poll_type == self::GROUP;
    }

    public function isDiscu()
    {
        return $this->isDiscu() == self::DISCU;
    }
}