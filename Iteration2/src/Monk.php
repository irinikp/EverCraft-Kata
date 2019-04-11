<?php

namespace Dnd;

/**
 * Class Monk
 * @package Dnd
 */
class Monk extends iClass
{

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 6;
    }

    /**
     * @return int
     */
    public function getDamage(): int
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
}