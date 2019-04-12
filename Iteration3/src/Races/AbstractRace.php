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
    const HUMAN = 'Human';
    const ORC = 'Orc';

    const TYPES = [
        self::HUMAN,
        self::ORC
    ];

    use GlobalCharacteristics;

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getStrengthModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::STR);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDexterityModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::DEX);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getConstitutionModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::CON);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getIntelligenceModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::INT);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getWisdomModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::WIS);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getCharismaModifier(Character $character): int
    {
        return $this->getAbilityModifier($character, Abilities::CHA);
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