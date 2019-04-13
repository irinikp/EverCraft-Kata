<?php

namespace Dnd\Races;

use Dnd\Abilities;
use Dnd\Character;

/**
 * Class Dwarf
 * @package Dnd\Races
 */
class Dwarf extends AbstractRace
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getConstitutionModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::CON);
        return 1 + $modifier;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getCharismaModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::CHA);
        return $modifier - 1;
    }

    /**
     * @return int
     */
    public function getHpModifier(Character $character): int
    {
        $con_modifier = parent::getHpModifier($character);
        if ($con_modifier > 0) {
            $con_modifier *= 2;
        }
        return $con_modifier;
    }

}