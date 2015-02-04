<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use ArrayAccess;
use RandomLib\Generator;
use SplObjectStorage;

class Solver implements MembersStorageInterface
{
    /**
     * @var Generator
     */
    protected $generator;

    /**
     * @var MembersStorageInterface
     */
    protected $storage;

    /**
     * @var SplObjectStorage
     */
    protected $members;

    /**
     * @var int
     */
    protected $upperLimit = 0;

    /**
     * @param Generator $generator
     * @param MembersStorageInterface $storage
     */
    public function __construct(Generator $generator, MembersStorageInterface $storage = null)
    {
        $this->generator = $generator;
        $this->members = new SplObjectStorage();
        $this->storage = ($storage) ? $storage : $this;
    }


    /**
     * @throws SolverException
     * @return mixed|object
     */
    public function run()
    {
        $random = $this->generator->generateInt(1, $this->storage->getUpperLimit());
        foreach ($this->storage->getAll() as $member) {
            $range = $this->storage->getRange($member);
            if ($random >= $range[0] && $random <= $range[1]) {
                return $member;
            }
        }

        throw new SolverException();
    }

    /**
     * @return void
     */
    protected function createRange()
    {
        $i = 0;
        foreach ($this->members as $member) {
            $this->members->offsetSet($member, [++$i, $i += $member->getChance() - 1]);
        }
    }

    /**
     * @param MemberInterface $member/
     * @return void
     */
    public function attach(MemberInterface $member)
    {
        if (!$this->contains($member)) {
            $this->members->attach($member);
            $this->upperLimit += $member->getChance();
            $this->createRange();
        }
    }

    /**
     * @param $member MemberInterface
     * @return bool
     */
    public function contains(MemberInterface $member)
    {
        return $this->members->contains($member);
    }

    /**
     * @param $member MemberInterface
     * @return void
     */
    public function detach(MemberInterface $member)
    {
        if ($this->contains($member)) {
            $this->members->detach($member);
            $this->upperLimit -= $member->getChance();
            $this->createRange();
        }
    }

    /**
     * @param MemberInterface $member
     * @return mixed|object
     */
    public function getRange(MemberInterface $member)
    {
        return $this->members->offsetGet($member);
    }

    /**
     * @return ArrayAccess
     */
    public function getAll()
    {
        return $this->members;
    }

    /**
     * @return int
     */
    public function getUpperLimit()
    {
        return $this->upperLimit;
    }
}
