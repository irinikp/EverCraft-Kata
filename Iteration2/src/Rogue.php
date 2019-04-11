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

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getTargetsAcModifier(Character $target): int
    {
        $target_ac = $target->getAc();
        $target_modifier = $target->getAbilityModifier('dexterity');
        if ($target_modifier > 0) {
            $target_ac -= $target_modifier;
        }
        return $target_ac;
    }
}