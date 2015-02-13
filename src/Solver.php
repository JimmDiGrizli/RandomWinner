<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;

use RandomLib\Generator;

class Solver
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
        $this->storage = $storage != null ? $storage : new SplStorage();
    }

    /**
     * Generate a winner.
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
     * Return members storage.
     * @return MembersStorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
