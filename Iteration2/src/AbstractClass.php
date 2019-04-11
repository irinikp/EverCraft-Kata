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

    public function __construct()
    {
        $this->attack_roll = 0;
    }

    /**
     * @param int $attack_roll
     */
    public function setAttackRoll(int $attack_roll): void
    {
        $this->attack_roll = $attack_roll;
    }

    /**
     * @var int
     */
    protected $attack_roll;

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
     * @param int            $level
     * @param int            $attack_roll
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
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
     * @param Character $target
     *
     * @return int
     */
    public function getDamage(Character $target): int
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

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getTargetsAcModifier(Character $target): int
    {
        return $target->getAc();
    }
}