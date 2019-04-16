<?php

namespace EverCraft\Classes;

use EverCraft\Abilities;
use EverCraft\Character;
use EverCraft\CoreBuild;

/**
 * Class iClass
 * @package EverCraft
 */
abstract class SocialClass extends CoreBuild
{
    const FIGHTER = 'Fighter';
    const ROGUE   = 'Rogue';
    const MONK    = 'Monk';
    const PALADIN = 'Paladin';
    const PRIEST  = 'Priest';

    const CLASS_TYPES = [
        self::FIGHTER,
        self::ROGUE,
        self::MONK,
        self::PALADIN,
        self::PRIEST
    ];

    /**
     * @var int
     */
    protected $attack_roll;

    /**
     * SocialClass constructor.
     */
    public function __construct()
    {
        $this->attack_roll = 0;
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
        return intval($level / 2);
    }

    /**
     * @param int $attack_roll
     */
    public function setAttackRoll(int $attack_roll): void
    {
        $this->attack_roll = $attack_roll;
    }

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 5;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        return 2;
    }

    /**
     * @return string
     */
    public function getAttackAbility(): string
    {
        return Abilities::STR;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getBasicAc(): int
    {
        return 10;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return parent::getAcModifier($character);
    }

    /**
     * @param Character $character
     * @param Character $attacker
     *
     * @return int
     */
    public function getAcModifierWhenUnderAttack(Character $character, Character $attacker): int
    {
        $ac = parent::getAcModifierWhenUnderAttack($character, $attacker);
        if (SocialClass::ROGUE === $attacker->getClassName()) {
            $dex_modifier = $character->getAbilityModifier(Abilities::DEX);
            if ($dex_modifier > 0) {
                $ac -= $dex_modifier;
            }
        }
        return $ac;
    }
}