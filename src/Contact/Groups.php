<?php


namespace QqBot\Contact;
use QqBot\Bot;

/**
 * Class Groups
 * @package QqBot\Contact
 * @property string $name 群名
 * @property integer $gid 群编号，用户发消息
 * @property integer $code 用户获取群详细信息
 */
class Groups implements \Iterator
{
    private $length;
    private $index = 0;
    private $datas;

    public function __construct($arr)
    {
        $this->datas = $arr;
        $this->length = count($arr['gnamelist']);
    }

    public function current()
    {
        return $this;
    }

    public function next()
    {
        $this->index++;
    }

    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return isset($this->datas['gnamelist'][$this->index]);
    }

    public function rewind()
    {
        return $this->index = 0;
    }

    public function getGroupInfo()
    {
        return Bot::$bot->getGroupInfo($this->datas['gnamelist'][$this->index]['code']);
    }

    public function __get($name)
    {
        switch ($name) {
            case 'gid':
                return $this->datas['gnamelist'][$this->index]['gid'];
            case 'code':
                return $this->datas['gnamelist'][$this->index]['code'];
            case 'name':
                return $this->datas['gnamelist'][$this->index]['name'];
            case 'count':
                return $this->length;
            default:
                throw new \InvalidArgumentException("不存在的属性：$$name");
        }
    }
}