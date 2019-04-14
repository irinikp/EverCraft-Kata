<?php

namespace EverCraft;

/**
 * Class CoreBuild
 * @package EverCraft
 */
abstract class CoreBuild
{
    const CRITICAL = 20;

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
     * @param string $alignment
     *
     * @return bool
     */
    public function isAlignmentAllowed(string $alignment): bool
    {
        $alignment = ucfirst($alignment);
        return in_array($alignment, $this->getAllowedAlignments());
    }

    /**
     * @return array<string>
     */
    public function getAllowedAlignments(): array
    {
        return Alignment::TYPE;
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

    /**
     * @param int $dice
     *
     * @return bool
     */
    public function isCritical($dice): bool
    {
        return ($dice === self::CRITICAL);
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return int
     */
    public function getTargetsAcModifier(Character $attacker, Character $target): int
    {
        return 0;
    }
}