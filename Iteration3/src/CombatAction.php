<?php

namespace EverCraft;

/**
 * Class CombatAction
 * @package EverCraft
 */
class CombatAction
{
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
        $hits = $this->hits($this->attacker->getAttackBonus($this->target));
        if ($hits) {
            $this->target->takeDamage($this->calculate_damage());
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
        return ($this->dice + $modifier) >= $this->target->getAc($this->attacker);
    }

    /**
     * @return int
     */
    protected function getDamage(): int
    {
        return $this->attacker->getDamage($this->target);
    }

    /**
     * @return int
     */
    protected function calculate_damage(): int
    {
        $damage = $this->getDamage();
        if ($this->attacker->isCritical($this->dice)) {
            $damage *= $this->attacker->getCriticalDamageMultiplier($this->target);
        }
        return max(1, $damage);
    }
}