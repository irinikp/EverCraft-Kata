<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;
use EverCraft\CoreBuild;

/**
 * Class Weapon
 * @package EverCraft\Items\Weapons
 */
abstract class Weapon extends CoreBuild
{
    const LONGSWORD = 'Longsword';
    const WARAXE    = 'Waraxe';

    const CLASS_TYPES = [
        self::LONGSWORD,
        self::WARAXE,
    ];

    /**
     * @var int
     */
    protected $magical;

    public function __construct($magical = 0)
    {
        $this->magical = $magical;
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
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
    {
        return $this->magical;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public static function belongs(string $type): bool
    {
        return in_array($type, self::CLASS_TYPES);
    }

    public function getCriticalDamageMultiplier(Character $target): int
    {
        return intval($this->magical / 2);
    }

}