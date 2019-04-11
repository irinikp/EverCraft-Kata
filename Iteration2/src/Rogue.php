<?php

namespace Dnd;

/**
 * Class Rogue
 * @package Dnd
 */
class Rogue extends AbstractClass
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

    /**
     * @param string $alignment
     *
     * @return bool
     */
    public function isAlignmentAllowed(string $alignment): bool
    {
        return ('Good' !== ucfirst($alignment));
    }
}