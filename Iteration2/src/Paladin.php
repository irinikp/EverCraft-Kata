<?php

namespace Dnd;

/**
 * Class Paladin
 * @package Dnd
 */
class Paladin extends AbstractClass
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
        $damage = 1;
        if ('Evil' === $target->getAlignment()) {
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
        $attack_roll = parent::getAttackRoll($level, $attack_roll, $target);
        if ($target && 'Evil' === $target->getAlignment()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }
}