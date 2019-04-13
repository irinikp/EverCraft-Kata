<?php

namespace Dnd\Classes;

use Dnd\Abilities;
use Dnd\Character;
use Dnd\Properties;

/**
 * Class iClass
 * @package Dnd
 */
abstract class AbstractClass extends Properties
{
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
     * @param string $type
     *
     * @return bool
     */
    public static function belongs(string $type): bool
    {
        return in_array($type, self::CLASS_TYPES);
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
        return Abilities::STR;
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
     * @return int
     */
    public function getBasicAc(): int
    {
        return 10;
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return int
     */
    public function getTargetsAcModifier(Character $attacker, Character $target): int
    {
        return $target->getAc();
    }
}