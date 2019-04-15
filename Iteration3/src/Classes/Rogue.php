<?php

namespace EverCraft\Classes;

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;

/**
 * Class Rogue
 * @package EverCraft
 */
class Rogue extends SocialClass
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
}