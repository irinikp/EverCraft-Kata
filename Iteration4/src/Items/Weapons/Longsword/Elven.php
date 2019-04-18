<?php

namespace EverCraft\Items\Weapons\Longsword;

use EverCraft\Character;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Races\Race;

/**
 * Class Elven
 * @package EverCraft\Items\Weapons\Longsword
 */
class Elven extends Longsword
{
    /**
     * Elven constructor.
     *
     * @param int $magical
     */
    public function __construct(int $magical = 0)
    {
        parent::__construct($magical);
        $this->magical = 1;
    }


    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        $damage = parent::getDamage($character);
        if (Race::ELF === $character->getRaceName()) {
            $damage++;
        }
        return $damage;
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
        if (Race::ELF === $attacker->getRaceName()) {
            $attack_roll++;
        }
        if ($target) {
            if (Race::ORC === $target->getRaceName()) {
                $attack_roll++;
            }
            if (Race::ELF === $attacker->getRaceName() && Race::ORC === $target->getRaceName()) {
                $attack_roll += 2;
            }
        }
        return $attack_roll;
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return int
     */
    public function getDamageModifierWhenAttacking(Character $attacker, Character $target): int
    {
        $damage = parent::getDamageModifierWhenAttacking($attacker, $target);
        if (Race::ORC === $target->getRaceName()) {
            $damage++;
            if (Race::ELF === $attacker->getRaceName()) {
                $damage += 2;
            }
        }
        return $damage;
    }

}