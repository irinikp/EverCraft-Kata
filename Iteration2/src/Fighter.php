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
     * @return int
     */
    public function getAttackRoll($level): int
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