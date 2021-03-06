<?php

namespace Dnd\Classes;

use Dnd\Character;

/**
 * Class Monk
 * @package Dnd
 */
class Monk extends AbstractClass
{

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 6;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getDamage(Character $target): int
    {
        return 3;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        $modifier        = $character->getAbilityModifier('dexterity');
        $wisdom_modifier = $character->getAbilityModifier('wisdom');
        if ($wisdom_modifier > 0) $modifier += $wisdom_modifier;
        return $modifier;
    }

    /**
     * @param int            $level
     * @param int            $attack_roll
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
    {
        if (1 === $level) return $attack_roll;
        if (0 === $level % 2 || 0 === $level % 3) {
            $attack_roll += 1;
        }
        return $this->getAttackRoll($level - 1, $attack_roll, $target);
    }
}