<?php

namespace EverCraft\Classes;

use EverCraft\Abilities;
use EverCraft\Character;

/**
 * Class Monk
 * @package EverCraft
 */
class Monk extends SocialClass
{

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 6;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
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
        $modifier        = parent::getAcModifier($character);
        $wisdom_modifier = $character->getAbilityModifier(Abilities::WIS);
        if ($wisdom_modifier > 0) $modifier += $wisdom_modifier;
        return $modifier;
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
        return $this->recursivellyComputeAttackRoll($level, 0, $attacker, $target);
    }

    /**
     * @param int            $level
     * @param int            $attack_roll
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    protected function recursivellyComputeAttackRoll($level, $attack_roll, Character $attacker, Character $target = null): int
    {
        if (1 === $level) return $attack_roll;
        if (0 === $level % 2 || 0 === $level % 3) {
            $attack_roll += 1;
        }
        return $this->recursivellyComputeAttackRoll($level - 1, $attack_roll, $attacker, $target);
    }
}