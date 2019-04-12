<?php

namespace Dnd\Races;

use Dnd\Character;

class Orc extends AbstractRace
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getStrengthModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, 'Strength');
        return $modifier + 2;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getIntelligenceModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, 'Intelligence');
        return $modifier - 1;
    }


    /**
     * @param Character $character
     *
     * @return int
     */
    public function getWisdomModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, 'Wisdom');
        return $modifier - 1;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getCharismaModifier(Character $character): int
    {
        $modifier = parent::getAbilityModifier($character, 'Charisma');
        return $modifier - 1;
    }

}