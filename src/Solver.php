<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use RandomLib\Generator;
use SplObjectStorage;
use Symfony\Component\Config\Definition\Exception\Exception;

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


    /**
     * @return mixed|object
     */
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

        throw new SolverException();
    }

    /**
     * @expectedException        SolverException
     * @expectedExceptionMessage
     */
    protected function createRange()
    {
        $i = 0;
        foreach ($this->members as $member) {
            $this->members[$member] = [++$i, $i += $member->getChance() - 1];
        }
    }

    /**
     * @param MemberInterface $member
     * @return void
     */
    public function attach(MemberInterface $member)
    {
        if (!$this->contains($member)) {
            $this->members->attach($member);
            $this->upperLimit += $member->getChance();
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
        }
    }
}
