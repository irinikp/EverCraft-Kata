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
     * @return int
     */
    public function getMovementSpeed(): int
    {
        return parent::getMovementSpeed() - 5;
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
     * @param int            $level
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, Character $attacker, Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($level, $attacker, $target);
        if ($target && Race::ORC === $target->getRaceName()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return int
     */
    public function getDamageModifierWhenAttacking(Character $attacker, Character $target): int
    {
        $damage = parent::getDamageModifierWhenAttacking($attacker, $target);
        if (Race::ORC === $target->getRaceName()) {
            $damage += 2;
        }
        return $damage;
    }
}