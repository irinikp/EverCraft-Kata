<?php

namespace EverCraft\Races;

use EverCraft\Abilities;
use EverCraft\Character;
use EverCraft\CoreBuild;

/**
 * Class Race
 * @package EverCraft\Races
 */
abstract class Race extends CoreBuild
{
    const HUMAN    = 'Human';
    const ORC      = 'Orc';
    const DWARF    = 'Dwarf';
    const ELF      = 'Elf';
    const HALFLING = 'Halfling';

    const RACE_TYPES = [
        self::HUMAN,
        self::ORC,
        self::DWARF,
        self::ELF,
        self::HALFLING,
    ];

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
    public function getDamage(Character $character): int
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
     * @return int
     */
    public function getHpModifier(Character $character): int
    {
        return $this->getConstitutionModifier($character);
    }

    /**
     * @param Character $character
     * @param Character $attacker
     *
     * @return int
     */
    public function getAcModifierWhenUnderAttack(Character $character, Character $attacker): int
    {
        $ac = parent::getAcModifierWhenUnderAttack($character, $attacker);
        if (Race::ELF === $character->getRaceName() && Race::ORC === $attacker->getRaceName()) {
            $ac += 2;
        }
         if (Race::HALFLING === $character->getRaceName() && Race::HALFLING !== $attacker->getRaceName()) {
            $ac += 2;
        }
        return $ac;
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