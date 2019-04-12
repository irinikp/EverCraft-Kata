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
     * @var int
     */
    protected $dice;

    /**
     * CombatAction constructor.
     *
     * @param Character $attacker
     * @param Character $target
     * @param int       $dice
     */
    public function __construct($attacker, $target, $dice)
    {
        $this->attacker = $attacker;
        $this->target   = $target;
        $this->dice     = $dice;
    }

    /**
     * @return bool
     */
    public function attackRoll(): bool
    {
        $hits = $this->hits($this->attacker->getAttackModifier());
        if ($hits) {
            $this->target->takeDamage($this->calculate_damage($this->attacker->getAbilityModifier('strength')));
            $this->attacker->gainSuccessfulAttackXp();
        }
        return $hits;
    }

    /**
     * @param int $modifier
     *
     * @return bool
     */
    protected function hits($modifier): bool
    {
        $target_ac = $this->attacker->getClass()->getTargetsAcModifier($this->target);
        return ($this->dice + $modifier + $this->attacker->getClass()
                    ->getAttackRoll($this->attacker->getLevel(), 0, $this->target)) >= $target_ac;
    }

    /**
     * @param int $modifier
     *
     * @return int
     */
    protected function calculate_damage($modifier): int
    {
        $damage = $this->attacker->getClass()->getDamage($this->target) + $modifier;
        if ($this->dice === self::CRITICAL) {
            $damage *= $this->attacker->getClass()->getCriticalDamageMultiplier($this->target);
        }
        return max(1, $damage);
    }
}