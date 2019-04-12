<?php

namespace Dnd\Classes;

use Dnd\Abilities;
use Dnd\Alignment;
use Dnd\Character;

/**
 * Class Rogue
 * @package Dnd
 */
class Rogue extends AbstractClass
{
    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        return 3;
    }

    /**
     * @return string
     */
    public function getAttackAbility(): string
    {
        return Abilities::DEX;
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
        return [
            Alignment::NEUTRAL,
            Alignment::EVIL,
        ];
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getTargetsAcModifier(Character $target): int
    {
        $target_ac       = $target->getAc();
        $target_modifier = $target->getAbilityModifier(Abilities::DEX);
        if ($target_modifier > 0) {
            $target_ac -= $target_modifier;
        }
        return $target_ac;
    }
}