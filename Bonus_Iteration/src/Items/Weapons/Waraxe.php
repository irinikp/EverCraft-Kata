<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;

/**
 * Class Waraxe
 * @package EverCraft\Items\Weapons
 */
class Waraxe extends Weapon
{
    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return parent::getDamage($character) + 6;
    }

}