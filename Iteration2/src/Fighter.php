<?php

namespace Dnd;

/**
 * Class Fighter
 * @package Dnd
 */
class Fighter extends AbstractClass
{
    /**
     * @param int            $level
     *
     * @param int            $attack_roll
     *
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
    {
        return $level - 1;
    }

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 10;
    }

}