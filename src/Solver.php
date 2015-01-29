<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use RandomLib\Generator;
use SplObjectStorage;

class Solver
{
    /**
     * @var Generator
     */
    protected $generator;

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
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        $this->members = new SplObjectStorage();
    }

    public function run()
    {
        $this->createRange();
        $random = $this->generator->generateInt(1, $this->upperLimit);
        foreach ($this->members as $member) {
            $range = $this->members[$member];
            if ($random >= $range[0]  && $random <= $range[1]) {
                return $member;
            }
        }
    }

    protected function createRange()
    {
        $i = 0;
        foreach ($this->members as $member) {
            $this->members[$member] = [++$i, $i += $member->getChance() - 1];
        }
    }

    /**
     * @param Member $member
     * @return void
     */
    public function attach(Member $member)
    {
        if (!$this->contains($member)) {
            $this->members->attach($member);
            $this->upperLimit += $member->getChance();
        }
    }

    /**
     * @param $member Member
     * @return bool
     */
    public function contains(Member $member)
    {
        return $this->members->contains($member);
    }

    /**
     * @param $member Member
     * @return void
     */
    public function detach(Member $member)
    {
        if ($this->contains($member)) {
            $this->members->detach($member);
            $this->upperLimit -= $member->getChance();
        }
    }
}
