<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use RandomLib\Generator;
use SplMaxHeap;

class Solver
{
    /**
     * @var Generator $generator
     */
    protected $generator;

    /**
     * @var array $objects
     */
    protected $objects;
    /**
     * @var array $list
     */
    protected $list;

    /**
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @param $object mixed
     * @param $chance int
     * @return bool
     */
    public function push($object, $chance)
    {
        $key = $this->hashObject($object);
        if (is_object($object)) {
            $this->objects[$key] = $object;
        }
        $this->list[$key] = (int)$chance;
        return true;
    }

    public function run()
    {
        arsort($this->list);
        $win = null;
        $random = null;
        foreach ($this->list as $key => $item) {
            if ($random === null) {
                $random = $this->generator->generateInt(1,$item);
            }
            if ($item >= $random) {
                $win = $key;
            }
        }

        if (isset($this->objects[$win])) {
            return $this->objects[$win];
        }

        return $win;
    }

    /**
     * @param $object mixed
     * @return bool
     */
    public function contains($object)
    {
        return isset($this->list[$this->hashObject($object)]);
    }

    /**
     * @param $object mixed
     * @return bool|int
     */
    public function chance($object)
    {
        if ($this->contains($object) === false) {
            return false;
        }
        return $this->list[$this->hashObject($object)];
    }

    /**
     * @param $object mixed
     * @return bool
     */
    public function delete($object)
    {
        $key = $this->hashObject($object);
        if ($this->contains($object) === false) {
            return false;
        }
        if (is_object($object)) {
            unset($this->objects[$key]);
        }
        unset($this->list[$key]);
        return true;
    }

    /**
     * @param $object mixed
     * @return string
     */
    protected function hashObject($object)
    {
        $hash = null;
        if (is_object($object)) {
            $hash = spl_object_hash($object);
        }
        return $hash?$hash:$object;
    }
}
