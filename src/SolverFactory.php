<?php
/**
 * @author  Alexander Getmansky <getmansk_y@yandex.ru>
 * @package GetSky\RandomWinner
 */
namespace GetSky\RandomWinner;


use RandomLib\Generator;

class SolverFactory
{
    public static function createSolver(Generator $generator, array $members, MembersStorageInterface $storage = null)
    {
        if ($storage == null) {
            $storage = new DefaultStorage();
        }

        foreach ($members as $member) {
            $storage->attach(new Member($member[0], $member[1]));
        }

        return new Solver($generator, $storage);
    }
}
