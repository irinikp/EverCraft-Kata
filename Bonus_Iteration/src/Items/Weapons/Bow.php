<?php

namespace EverCraft\Items\Weapons;


use EverCraft\Character;

/**
 * Class Bow
 * @package EverCraft\Items\Weapons
 */
class Bow extends MissileWeapon
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return parent::getDamage($character) + 5;
    }
}