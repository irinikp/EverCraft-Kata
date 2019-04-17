<?php


namespace EverCraft\Items\Shields;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\CoreBuild;
use EverCraft\Items\Item;
use EverCraft\Races\Race;

/**
 * Class Shield
 * @package EverCraft\Items\Shields
 */
class Shield extends CoreBuild implements Item
{

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return false;
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
     * @param int            $attack_roll
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $attacker, Character $target = null): int
    {
        $attack_roll = parent::getAttackRoll($level, $attack_roll, $attacker, $target);
        $attack_roll -= 4;
        if (SocialClass::FIGHTER === $attacker->getClassName()) {
            $attack_roll += 2;
        }
        return $attack_roll;
    }
}