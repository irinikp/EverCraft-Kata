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
        $hits = $this->hits($this->attacker->getAbilityModifier('strength'));
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
        $target_ac = $this->target->getAc();
        if ('Rogue' === $this->attacker->getClassName()) {
            $target_modifier = $this->target->getAbilityModifier('dexterity');
            if ($target_modifier > 0) {
                $target_ac -= $target_modifier;
            }
        }
        return ($this->dice + $modifier + $this->attacker->getAttackRoll()) >= $target_ac;
    }

    /**
     * @param int $modifier
     *
     * @return int
     */
    protected function calculate_damage($modifier): int
    {
        $damage = 1 + $modifier;
        if ($this->dice === self::CRITICAL) {
            $damage = $this->attacker->getClass()->getCriticalDamage($damage);
        }
        return max(1, $damage);
    }
}