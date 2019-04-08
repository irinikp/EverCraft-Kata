<?php

namespace Dnd;


/**
 * Class Abilities
 * @package Dnd
 */
class Abilities
{
    /**
     * @var int
     */
    protected $strength;
    /**
     * @var int
     */
    protected $dexterity;
    /**
     * @var int
     */
    protected $constitution;
    /**
     * @var int
     */
    protected $intelligence;
    /**
     * @var int
     */
    protected $wisdom;
    /**
     * @var int
     */
    protected $charisma;

    /**
     * Abilities constructor.
     */
    public function __construct()
    {
        $this->strength     = 10;
        $this->dexterity    = 10;
        $this->constitution = 10;
        $this->intelligence = 10;
        $this->wisdom       = 10;
        $this->charisma     = 10;
    }

    /**
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     */
    public function setStrength(int $strength): void
    {
        if ($this->isValidAbilitiesRange($strength)) $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getDexterity(): int
    {
        return $this->dexterity;
    }

    /**
     * @param int $dexterity
     */
    public function setDexterity(int $dexterity): void
    {
        if ($this->isValidAbilitiesRange($dexterity)) $this->dexterity = $dexterity;
    }

    /**
     * @return int
     */
    public function getConstitution(): int
    {
        return $this->constitution;
    }

    /**
     * @param int $constitution
     */
    public function setConstitution(int $constitution): void
    {
        if ($this->isValidAbilitiesRange($constitution)) $this->constitution = $constitution;
    }

    /**
     * @return int
     */
    public function getIntelligence(): int
    {
        return $this->intelligence;
    }

    /**
     * @param int $intelligence
     */
    public function setIntelligence(int $intelligence): void
    {
        if ($this->isValidAbilitiesRange($intelligence)) $this->intelligence = $intelligence;
    }

    /**
     * @return int
     */
    public function getWisdom(): int
    {
        return $this->wisdom;
    }

    /**
     * @param int $wisdom
     */
    public function setWisdom(int $wisdom): void
    {
        if ($this->isValidAbilitiesRange($wisdom)) $this->wisdom = $wisdom;
    }

    /**
     * @return int
     */
    public function getCharisma(): int
    {
        return $this->charisma;
    }

    /**
     * @param int $charisma
     */
    public function setCharisma(int $charisma): void
    {
        if ($this->isValidAbilitiesRange($charisma)) $this->charisma = $charisma;
    }

    private function isValidAbilitiesRange($value): bool
    {
        return $value > 0 && $value <= 20;
    }
}