<?php

namespace Dnd\Races;

use Dnd\Abilities;
use Dnd\Character;

/**
 * Class Dwarf
 * @package Dnd\Races
 */
class Dwarf extends AbstractRace
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
    public function getHpModifier(Character $character): int
    {
        $con_modifier = parent::getHpModifier($character);
        if ($con_modifier > 0) {
            $con_modifier *= 2;
        }
        return $con_modifier;
    }

    /**
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll(Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($target);
        if ($target && AbstractRace::ORC === $target->getRaceName()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }


    /**
     * @param Character $target
     *
     * @return int
     */
    public function getDamage(Character $target): int
    {
        $damage = parent::getDamage($target);
        if ($target && AbstractRace::ORC === $target->getRaceName()) {
            $damage += 2;
        }
        return $damage;
    }
}