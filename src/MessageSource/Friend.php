<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-22
 * Time: 上午9:17
 */

namespace QqBot\MessageSource;

// 好友消息
class Friend extends MessageAbstract
{
    public $poll_type = self::FRIEND;

    public $content;

    public $from_uin;
    public $msg_id;
    public $time;
    public $to_uin;

    public function __construct($data)
    {
        $data = $data['value'];
        $this->content = $data['content'][1];
        $this->from_uin = $data['from_uin'];
        $this->time = $data['time'];
        $this->to_uin = $data['to_uin'];
        $this->msg_id = $data['msg_id'];
    }
}