<?php

namespace Dnd\Races;

use Dnd\Abilities;
use Dnd\Character;
use GlobalCharacteristics;

/**
 * Class AbstractRace
 * @package Dnd\Races
 */
abstract class AbstractRace
{
    const TYPES = [
        'Human',
        'Orc',
    ];

    use GlobalCharacteristics;

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getStrengthModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Strength');
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDexterityModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Dexterity');
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getConstitutionModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Constitution');
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getIntelligenceModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Intelligence');
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getWisdomModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Wisdom');
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getCharismaModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, 'Charisma');
    }

    /**
     * @param Character $character
     * @param string    $ability
     *
     * @return int
     */
    protected function getAbilityModifier(Character $character, $ability): int
    {
        $ability = 'get' . ucfirst($ability);
        return Abilities::MODIFIER[$character->getAbilities()->$ability()];
    }
}