<?php

namespace Dnd\Races;

use Dnd\Abilities;
use Dnd\Character;

/**
 * Class Orc
 * @package Dnd\Races
 */
class Orc extends Race
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getStrengthModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::STR);
        return $modifier + 2;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getIntelligenceModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::INT);
        return $modifier - 1;
    }


    /**
     * @param Character $character
     *
     * @return int
     */
    public function getWisdomModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, Abilities::WIS);
        return $modifier - 1;
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
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        $modifier        = parent::getAcModifier($character);
        return $modifier + 2;
    }

}