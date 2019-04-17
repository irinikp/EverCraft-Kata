<?php


namespace EverCraft\Items\Armors\ChainMail;

use EverCraft\Character;
use EverCraft\Items\Armors\ChainMail;
use EverCraft\Races\Race;

/**
 * Class Elven
 * @package EverCraft\Items\Armors\ChainMail
 */
class Elven extends ChainMail
{

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        $modifier = 5;
        if (Race::ELF === $character->getRaceName()) {
            $modifier += 3;
        }
        return $modifier;
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
        if (Race::ELF === $attacker->getRaceName()) {
            $attack_roll++;
        }
        return $attack_roll;
    }
}