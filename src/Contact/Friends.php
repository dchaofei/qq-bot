<?php


namespace QqBot\Contact;
use QqBot\Bot;

/**
 * Class Friend
 * @package QqBot\Contact
 * @property int $uin;
 * @property string $markname;
 * @property string $nickname;
 * @property string $categories_name;
 * @property int $categories_index;
 * @property int $categories_sort;
 * @property bool $is_vip;
 * @property int $vip_level;
 */
class Friends implements \Iterator
{
    /** @var int 用户编号，通过编号请求接口获取 qq 号 */
    private $uin;

    /** @var string 备注 */
    private $markname;

    /** @var string 昵称 */
    private $nickname;

    /** @var string 分组名 */
    private $categories_name;

    /** @var int 分组编号号 */
    private $categories_index;

    /** @var int 分组排序 */
    private $categories_sort;

    /** @var bool Vip */
    private $is_vip;

    /** @var int vip 等级 */
    private $vip_level;

    private $index = 0;
    private $datas;
    private $length = 0;
    private $count;

    public function __construct($arr)
    {
        $this->datas = $arr;
        $this->length = count($arr['friends']);
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
        return isset($this->datas['friends'][$this->index]);
    }

    public function rewind()
    {
        return $this->index = 0;
    }

    public function getFriendInfo()
    {
        return Bot::$bot->getFriendInfo($this->__get('uin'));
    }

    public function getOnlineStatus()
    {
        return Bot::$bot->getOnlineStatus($this->__get('uin'));
    }

    public function __get($name)
    {
        switch ($name) {
            case 'uin':
                return $this->datas['friends'][$this->index]['uin'];
            case 'markname':
                return $this->datas['marknames'][$this->index]['markname'];
            case 'nickname':
                return $this->datas['info'][$this->index]['nick'];
            case 'categories_name':
                return $this->datas['categories'][$this->datas['friends'][$this->index]['categories']]['name'];
            case 'categories_index':
                return $this->datas['categories'][$this->datas['friends'][$this->index]['categories']]['index'];
            case 'categories_sort':
                return $this->datas['categories'][$this->datas['friends'][$this->index]['categories']]['sort'];
            case 'is_vip':
                return (bool)$this->datas['vipinfo'][$this->index]['is_vip'];
            case 'vip_level':
                return $this->datas['vipinfo'][$this->index]['vip_level'];
            case 'count':
                return $this->length;
            default:
                throw new \InvalidArgumentException("不存在的属性：$$name");
        }
    }
}