<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;


use SplObjectStorage;
use Traversable;

class SplStorage implements MembersStorageInterface
{

    /**
     * @var SplObjectStorage
     */
    protected $members;

    /**
     * @var int
     */
    protected $upperLimit = 0;

    public function __construct()
    {
        $this->members = new SplObjectStorage();
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
     * Attach member.
     * @param MemberInterface $member
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
     * @return \Traversable
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
