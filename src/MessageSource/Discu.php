<?php


namespace QqBot\MessageSource;


class Discu extends Group
{
    public $poll_type = self::DISCU;
    public $group_code;
    public $dis;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->dis = $this->group_code;
    }
}