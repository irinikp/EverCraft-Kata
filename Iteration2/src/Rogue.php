<?php

namespace Dnd;

/**
 * Class Rogue
 * @package Dnd
 */
class Rogue extends iClass
{
    /**
     * @param int $damage
     *
     * @return int
     */
    public function getCriticalDamage($damage): int
    {
        return 3 * $damage;
    }

    /**
     * @return string
     */
    public function getAttackAbility(): string
    {
        return 'dexterity';
    }
}