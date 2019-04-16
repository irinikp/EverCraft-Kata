<?php

namespace EverCraft\Items\Weapons;

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
     * @param string $type
     *
     * @return bool
     */
    public static function belongs(string $type): bool
    {
        return in_array($type, self::CLASS_TYPES);
    }

}