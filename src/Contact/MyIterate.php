<?php


namespace QqBot\Contact;


class MyIterate extends \IteratorIterator implements \Countable
{
    public function __construct(\Traversable $iterator)
    {
        parent::__construct($iterator);
    }

    public function count()
    {
        return $this->getInnerIterator()->count;
    }
}