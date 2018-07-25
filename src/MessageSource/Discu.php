<?php


namespace QqBot\MessageSource;


class Discu extends Group
{
    public $poll_type = self::DISCU;
    public $group_code;
    public $did;

    public function __construct($data)
    {
        $data = $data['value'];
        $this->setContent($data['content']);
        $this->from_uin = $data['from_uin'];
        $this->did = $data['did'];
        $this->send_uin = $data['send_uin'];
        $this->time = $data['time'];
        $this->to_uin = $data['to_uin'];
        $this->msg_id = $data['msg_id'];
    }
}