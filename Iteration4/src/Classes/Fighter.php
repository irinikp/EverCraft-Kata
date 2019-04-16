<?php

namespace EverCraft\Classes;

use EverCraft\Character;

/**
 * Class Fighter
 * @package EverCraft
 */
class Fighter extends SocialClass
{
    /**
     * @param int            $level
     * @param int            $attack_roll
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $attacker, Character $target = null): int
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