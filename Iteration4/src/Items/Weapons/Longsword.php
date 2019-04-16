<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;

/**
 * Class Longsword
 * @package EverCraft\Items\Weapons
 */
class Longsword extends Weapon
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return 5;
    }

}