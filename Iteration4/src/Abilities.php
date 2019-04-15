<?php

namespace EverCraft;


/**
 * Class Abilities
 * @package EverCraft
 */
class Abilities
{
    const MODIFIER = [
        1  => -5,
        2  => -4,
        3  => -4,
        4  => -3,
        5  => -3,
        6  => -2,
        7  => -2,
        8  => -1,
        9  => -1,
        10 => 0,
        11 => 0,
        12 => 1,
        13 => 1,
        14 => 2,
        15 => 2,
        16 => 3,
        17 => 3,
        18 => 4,
        19 => 4,
        20 => 5
    ];

    const STR = 'Strength';
    const DEX = 'Dexterity';
    const CON = 'Constitution';
    const INT = 'Intelligence';
    const WIS = 'Wisdom';
    const CHA = 'Charisma';

    const TYPE = [
        self::STR,
        self::DEX,
        self::CON,
        self::INT,
        self::WIS,
        self::CHA
    ];

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
     * @param string $ability
     *
     * @return int
     */
    public static function getModifier($ability): int
    {
        return self::MODIFIER[$ability];
    }

    /**
     * @param string $ability
     *
     * @return bool
     */
    public static function isAbilityType($ability): bool
    {
        return in_array($ability, self::TYPE);
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

    /**
     * @param int $value
     *
     * @return bool
     */
    private function isValidAbilitiesRange($value): bool
    {
        return $value > 0 && $value <= 20;
    }
}