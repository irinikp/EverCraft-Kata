<?php

namespace Dnd;

/**
 * Class iClass
 * @package Dnd
 */
abstract class iClass
{
    const TYPES = [
        'Fighter' => true,
        'Rogue'   => true,
        'Monk'    => true
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
     * @return int
     */
    public function getAttackRoll($level): int
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
}