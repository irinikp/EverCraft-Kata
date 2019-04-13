<?php

namespace Dnd\Races;

use Dnd\Abilities;
use Dnd\Character;

class Elf extends Race
{

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
    public function getConstitutionModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::CON);
        return $modifier - 1;
    }

    /**
     * @param int $dice
     *
     * @return bool
     */
    public function isCritical($dice): bool
    {
        return ($dice >= (self::CRITICAL - 1));
    }

}