<?php

namespace EverCraft\Races;

use EverCraft\Abilities;
use EverCraft\Character;

/**
 * Class Dwarf
 * @package EverCraft\Races
 */
class Dwarf extends Race
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getConstitutionModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::CON);
        return 1 + $modifier;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getCharismaModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::CHA);
        return $modifier - 1;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getHpModifier(Character $character): int
    {
        $con_modifier = parent::getHpModifier($character);
        if ($con_modifier > 0) {
            $con_modifier *= 2;
        }
        return $con_modifier;
    }

    /**
     * @param                $level
     * @param int            $attack_roll
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($level, $attack_roll, $target);
        if ($target && Race::ORC === $target->getRaceName()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getDamageModifierWhenAttacking(Character $target): int
    {
        $damage = parent::getDamageModifierWhenAttacking($target);
        if (Race::ORC === $target->getRaceName()) {
            $damage += 2;
        }
        return $damage;
    }
}