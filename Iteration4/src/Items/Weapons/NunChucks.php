<?php

namespace EverCraft\Items\Weapons;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;

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
     * @param                $level
     * @param int            $attack_roll
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $attacker, Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($level, $attack_roll, $attacker, $target);
        if (SocialClass::MONK !== $attacker->getClassName()) {
            $attack_roll -= 4;
        }
        return $attack_roll;
    }

}