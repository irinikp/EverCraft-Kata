<?php

namespace EverCraft\Races;

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;

class Halfling extends Race
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getStrengthModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::DEX);
        return $modifier - 1;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDexterityModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::DEX);
        return 1 + $modifier;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return $this->getStrengthModifier($character);
    }

    /**
     * @return array<string>
     */
    public function getAllowedAlignments(): array
    {
        return [
            Alignment::GOOD,
            Alignment::NEUTRAL,
        ];
    }
}