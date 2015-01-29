<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

class Member
{
    /**
    * @var mixed
    */
    protected $object;
    /**
     * @var int
     */
    protected $chance;

    /**
     * @param $object mixed
     * @param $chance int
     */
    public function __construct($object, $chance)
    {
        $this->object = $object;
        $this->chance = (int)$chance;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @return int
     */
    public function getChance()
    {
        return $this->chance;
    }
}
