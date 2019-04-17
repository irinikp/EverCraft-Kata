<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;
use EverCraft\CoreBuild;
use EverCraft\Items\Item;

/**
 * Class Weapon
 * @package EverCraft\Items\Weapons
 */
abstract class Weapon extends CoreBuild implements Item
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
     * Weapon constructor.
     *
     * @param int $magical
     */
    public function __construct($magical = 0)
    {
        $this->magical = $magical;
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
     * @param int            $attack_roll
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $attacker, Character $target = null): int
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

}