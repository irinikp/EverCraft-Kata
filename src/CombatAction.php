<?php

namespace Dnd;

/**
 * Class CombatAction
 * @package Dnd
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
    public function attackRoll()
    {
        $dice = $this->attacker->roll(20);
        $hits = $this->hits($dice);
        if ($hits) {
            $this->target->setHp($this->target->getHp() - 1);
        }
        return $hits;
    }

    /**
     * @param int $dice
     *
     * @return bool
     */
    public function hits($dice)
    {
        return $dice >= $this->target->getAc();
    }
}