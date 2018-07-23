<?php


namespace QqBot\MessageSource;


use QqBot\Bot;

class Group extends MessageAbstract
{
    public $poll_type = 'group_message';

    public $from_uin;
    public $group_code;
    public $msg_id;
    public $time;
    public $send_uin;
    public $to_uin;
    public $content;
    public $at = false;

    /** @var array */
    public $at_peoples;

    public function __construct($data)
    {
        $data = $data['value'];
        $this->setContent($data['content']);
        $this->from_uin = $data['from_uin'];
        $this->group_code = $data['group_code'];
        $this->send_uin = $data['send_uin'];
        $this->time = $data['time'];
        $this->to_uin = $data['to_uin'];
        $this->msg_id = $data['msg_id'];
    }

    private function setContent($content)
    {
        unset($content[0]);
        $this->filterArrayAndNull($content);
        $count = count($content);

        if ($count == 1) {
            return $this->content = trim(implode('', $content));
        }

        return $this->content = trim(implode('', $content));
    }

    private function filterArrayAndNull(&$array)
    {
        $iterator = new \RecursiveArrayIterator($array);
        foreach ($iterator as $key => $value) {
            if (empty(trim($value)) || is_array($value)) {
                unset($array[$key]);
            }

            if (stripos($value, '@') === 0) {
                $this->at = true;
                $this->at_peoples[] = $value;
                unset($array[$key]);
            }
        }
    }

    /**
     * 是否 @ 自己
     *
     * @return bool
     */
    public function isAtSelf()
    {
        foreach ($this->at_peoples as $v) {
            if ($v == Bot::getNickName()) {
                return true;
            }
        }
        return false;
    }
}