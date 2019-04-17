<?php


namespace EverCraft\Items;


use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;

class AmuletOfTheHeavens extends Item
{

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return true;
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
        $attack_roll = 0;
        if (Alignment::NEUTRAL === $target->getAlignment()) {
            $attack_roll += 1;
        } elseif (Alignment::EVIL === $target->getAlignment()) {
            $attack_roll += 2;
        }
        if (SocialClass::PALADIN === $attacker->getClassName()) {
            $attack_roll *= 2;
        }
        $attack_roll += parent::getAttackRoll($level, $attack_roll, $attacker, $target);
        return $attack_roll;
    }

}