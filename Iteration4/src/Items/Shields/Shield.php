<?php


namespace EverCraft\Items\Shields;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\Items\Item;

/**
 * Class Shield
 * @package EverCraft\Items\Shields
 */
class Shield extends Item
{

    /**
     * @param Character $character
     */
    public function wear(Character $character): void
    {
        $character->setShield($this);
    }

    /**
     * @param Character $character
     */
    public function remove(Character $character): void
    {
        $character->setShield(null);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return 3;
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
        $attack_roll -= 4;
        if (SocialClass::FIGHTER === $attacker->getClassName()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }
}