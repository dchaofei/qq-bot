<?php
/**
 * Created by PhpStorm.
 * User: chaofei
 * Date: 18-7-22
 * Time: 上午9:17
 */

namespace QqBot\Message;

// 好友消息
class Message
{
    public $poll_type = 'message';

    public $content;

    public $from_uin;
    public $msg_id;
    public $time;
    public $to_uni;

    public function __construct($data)
    {
        $data = $data['value'];
        $this->content = $data['content'][1];
        $this->from_uni = $data['from_uin'];
        $this->time = $data['time'];
        $this->to_uin = $data['to_uin'];
    }
}