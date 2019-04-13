<?php

namespace Dnd;

abstract class Properties
{
    const HUMAN = 'Human';
    const ORC   = 'Orc';
    const DWARF = 'Dwarf';
    const ELF   = 'Elf';

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

    const RACE_TYPES = [
        self::HUMAN,
        self::ORC,
        self::DWARF,
        self::ELF
    ];

    /**
     * @param string $type
     *
     * @return bool
     */
    public abstract static function belongs(string $type): bool;

    /**
     * @return int
     */
    public function getHpModifier(Character $character): int
    {
        return 0;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getDamage(Character $target): int
    {
        return 0;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return 0;
    }

    /**
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, $attack_roll = 0, Character $target = null): int
    {
        return 0;
    }

}