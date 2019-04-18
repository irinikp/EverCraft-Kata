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
     * @return int
     */
    public function getHpModifier(Character $character): int
    {
        return 0;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getDamage(Character $character): int
    {
        return 0;
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return int
     */
    public function getDamageModifierWhenAttacking(Character $attacker, Character $target): int
    {
        return 0;
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
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
     * @param Character $character
     * @param Character $attacker
     *
     * @return int
     */
    public function getAcModifierWhenUnderAttack(Character $character, Character $attacker): int
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
     * @param int            $level
     * @param Character      $attacker
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackRoll($level, Character $attacker, Character $target = null): int
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
     * @return int
     */
    public function getDamageReceiving(): int
    {
        return 0;
    }
}