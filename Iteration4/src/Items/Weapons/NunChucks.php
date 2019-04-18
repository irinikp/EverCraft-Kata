<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;

/**
 * Class NunChucks
 * @package EverCraft\Items\Weapons
 */
class NunChucks extends Weapon
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

    /**
     * @param int            $level
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, Character $attacker, Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($level, $attacker, $target);
        if (SocialClass::MONK !== $attacker->getClassName()) {
            $attack_roll -= 4;
        }
        return $attack_roll;
    }

}