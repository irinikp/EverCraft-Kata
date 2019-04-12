<?php

namespace Dnd\Classes;

use Dnd\Alignment;
use Dnd\Character;

/**
 * Class iClass
 * @package Dnd
 */
abstract class AbstractClass
{
    const TYPES = [
        'Fighter',
        'Rogue',
        'Monk',
        'Paladin',
    ];
    /**
     * @var int
     */
    protected $attack_roll;

    /**
     * AbstractClass constructor.
     */
    public function __construct()
    {
        $this->attack_roll = 0;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public static function isClassType(string $class): bool
    {
        return in_array($class, self::TYPES);
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
     * @param int $attack_roll
     */
    public function setAttackRoll(int $attack_roll): void
    {
        $this->attack_roll = $attack_roll;
    }

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 5;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        return 2;
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
        $alignment = ucfirst($alignment);
        return in_array($alignment, $this->getAllowedAlignments());
    }

    /**
     * @return array<string>
     */
    public function getAllowedAlignments(): array
    {
        return Alignment::TYPE;
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