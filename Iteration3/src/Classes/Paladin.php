<?php

namespace EverCraft\Classes;

use EverCraft\Alignment;
use EverCraft\Character;

/**
 * Class Paladin
 * @package EverCraft
 */
class Paladin extends SocialClass
{

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 8;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getDamage(Character $target): int
    {
        $damage = parent::getDamage($target);
        if ($target && Alignment::EVIL === $target->getAlignment()) {
            $damage += 2;
        }
        return $damage;
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
        $attack_roll = $level - 1;
        if ($target && Alignment::EVIL === $target->getAlignment()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        $multiplier = 2;
        if (Alignment::EVIL === $target->getAlignment()) {
            $multiplier++;
        }
        return $multiplier;
    }

    /**
     * @return array<string>
     */
    public function getAllowedAlignments(): array
    {
        return [
            Alignment::GOOD,
        ];
    }
}