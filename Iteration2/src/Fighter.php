<?php

namespace Dnd;

/**
 * Class Fighter
 * @package Dnd
 */
class Fighter extends iClass
{
    /**
     * @param int $level
     *
     * @param int $attack_roll
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0): int
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