<?php

namespace Dnd;

/**
 * Class iClass
 * @package Dnd
 */
abstract class AbstractClass
{
    const TYPES = [
        'Fighter' => true,
        'Rogue'   => true,
        'Monk'    => true,
        'Paladin' => true,
    ];

    /**
     * @param string $class
     *
     * @return bool
     */
    public static function isClassType(string $class): bool
    {
        return array_key_exists($class, self::TYPES);
    }

    /**
     * @param int $level
     *
     * @param int $attack_roll
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0): int
    {
        return intval($level / 2);
    }

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 5;
    }

    /**
     * @param int $damage
     *
     * @return int
     */
    public function getCriticalDamage($damage): int
    {
        return 2 * $damage;
    }

    /**
     * @return string
     */
    public function getAttackAbility(): string
    {
        return 'strength';
    }

    /**
     * @param string $alignment
     *
     * @return bool
     */
    public function isAlignmentAllowed(string $alignment): bool
    {
        return true;
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return 1;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return $character->getAbilityModifier('dexterity');
    }

    /**
     * @return int
     */
    public function getBasicAc(): int
    {
        return 10;
    }
}