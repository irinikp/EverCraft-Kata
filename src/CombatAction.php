<?php

namespace Dnd;

/**
 * Class CombatAction
 * @package Dnd
 */
class CombatAction
{
    const CRITICAL = 20;
    /**
     * @var Character
     */
    protected $attacker;
    /**
     * @var Character
     */
    protected $target;

    /**
     * CombatAction constructor.
     *
     * @param Character $attacker
     * @param Character $target
     */
    public function __construct($attacker, $target)
    {
        $this->attacker = $attacker;
        $this->target   = $target;
    }

    /**
     * @return bool
     */
    public function attackRoll(): bool
    {
        $dice = $this->attacker->roll(20);
        $hits = $this->hits($dice, $this->attacker->getAbilityModifier('strength'));
        if ($hits) {
            $this->target->damage($this->calculate_damage($dice, $this->attacker->getAbilityModifier('strength')));
        }
        return $hits;
    }

    /**
     * @param int $dice
     * @param int $modifier
     *
     * @return bool
     */
    protected function hits($dice, $modifier): bool
    {
        return ($dice + $modifier) >= $this->target->getAc();
    }

    /**
     * @param int $dice
     * @param int $modifier
     *
     * @return int
     */
    protected function calculate_damage($dice, $modifier): int
    {
        $damage = 1 + $modifier;
        if ($dice === self::CRITICAL) {
            $damage *= 2;
        }
        return max(1, $damage);
    }
}