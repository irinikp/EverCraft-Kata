<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;
use EverCraft\Items\Item;

/**
 * Class Weapon
 * @package EverCraft\Items\Weapons
 */
abstract class Weapon extends Item
{
    const LONGSWORD       = 'Longsword';
    const WARAXE          = 'Waraxe';
    const ELVEN_LONGSWORD = 'Longsword\\Elven';
    const NUNCHUCKS       = 'NunChucks';

    const CLASS_TYPES = [
        self::LONGSWORD,
        self::WARAXE,
        self::ELVEN_LONGSWORD,
    ];

    /**
     * @var int
     */
    protected $magical;
    /**
     * @var int
     */
    protected $min_range;
    /**
     * @var int
     */
    protected $max_range;

    /**
     * Weapon constructor.
     *
     * @param int $magical
     */
    public function __construct($magical = 0)
    {
        $this->magical   = $magical;
        $this->min_range = 1;
        $this->max_range = 1;
    }

    /**
     * @return int
     */
    public function getMaxRange(): int
    {
        return $this->max_range;
    }

    /**
     * @param int $max_range
     */
    public function setMaxRange(int $max_range): void
    {
        $this->max_range = $max_range;
    }

    /**
     * @return int
     */
    public function getMinRange(): int
    {
        return $this->min_range;
    }

    /**
     * @param int $min_range
     */
    public function setMinRange(int $min_range): void
    {
        $this->min_range = $min_range;
    }

    /**
     * @param Character $character
     */
    public function wear(Character $character): void
    {
        $character->setWeapon($this);
    }

    /**
     * @param Character $character
     */
    public function remove(Character $character): void
    {
        $character->setWeapon(null);
    }

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return $this->magical > 0;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return $this->magical;
    }

    /**
     * @param int            $level
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, Character $attacker, Character $target = null): int
    {
        return $this->magical;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        return intval($this->magical / 2);
    }

    /**
     * @return bool
     */
    public function isMissile()
    {
        return false;
    }

}