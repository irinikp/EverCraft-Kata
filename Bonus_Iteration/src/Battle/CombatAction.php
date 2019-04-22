<?php

namespace EverCraft\Battle;

use EverCraft\Character;
use EverCraft\Map\BattleGrid;
use EverCraft\Map\CartesianPoint;
use EverCraft\Map\MovementException;

/**
 * Class CombatAction
 * @package EverCraft
 */
class CombatAction
{
    const ATTACK = 'Attack';
    const MOVE   = 'Move';
    /**
     * @var Character
     */
    protected $player;
    /**
     * @var Character
     */
    protected $target;
    /**
     * @var int
     */
    protected $dice;
    /**
     * @var BattleGrid
     */
    protected $battle_grid;
    /**
     * @var string
     */
    protected $action;
    /**
     * @var array<CartesianPoint>
     */
    protected $route;

    /**
     * CombatAction constructor.
     *
     * @param string $action
     */
    public function __construct($action)
    {
        $this->action = $action;
    }

    /**
     * @return Character
     */
    public function getPlayer(): Character
    {
        return $this->player;
    }

    /**
     * @param Character $player
     */
    public function setPlayer(Character $player): void
    {
        $this->player = $player;
    }

    /**
     * @return bool
     */
    public function attackRoll(): bool
    {
        $hits = $this->hits($this->player->getAttackBonus($this->battle_grid, $this->target));
        if ($hits) {
            $this->target->takeDamage($this->calculate_damage());
            $this->player->gainSuccessfulAttackXp();
        }
        return $hits;
    }

    /**
     * @param Character  $attacker
     * @param Character  $target
     * @param int        $dice
     * @param BattleGrid $battle_grid
     *
     * @throws \Exception
     */
    public function setUpAttack(Character $attacker, Character $target, int $dice, BattleGrid $battle_grid): void
    {
        if (self::ATTACK === $this->action) {
            $this->player      = $attacker;
            $this->target      = $target;
            $this->dice        = $dice;
            $this->battle_grid = $battle_grid;
        } else {
            throw new \Exception('This combat action is not an attack');
        }
    }

    /**
     * @param Character             $player
     * @param BattleGrid            $battle_grid
     * @param array<CartesianPoint> $route
     *
     * @throws \Exception
     */
    public function setUpMovement(Character $player, BattleGrid $battle_grid, array $route)
    {
        if (self::MOVE === $this->action) {
            $this->player      = $player;
            $this->battle_grid = $battle_grid;
            $this->route       = $route;
        } else {
            throw new \Exception('This combat action is not a move');
        }
    }

    /**
     *
     */
    public function perform(): void
    {
        if (!$this->player->isDead()) {
            if (self::ATTACK === $this->action) {
                $this->attackRoll();
            } elseif (self::MOVE === $this->action) {
                $this->move();
            }
        }
    }

    /**
     * @throws MovementException
     */
    public function move(): void
    {
        $this->battle_grid->moveCharacter($this->player, $this->route);
    }

    /**
     * @param int $modifier
     *
     * @return bool
     */
    protected function hits($modifier): bool
    {
        return ($this->dice + $modifier) >= $this->target->getAc($this->player);
    }

    /**
     * @return int
     */
    protected function getDamage(): int
    {
        return $this->player->getDamage($this->target);
    }

    /**
     * @return int
     */
    protected function calculate_damage(): int
    {
        $damage = $this->getDamage();
        if ($this->player->isCritical($this->dice)) {
            $damage *= $this->player->getCriticalDamageMultiplier($this->target);
        }
        $damage = max(1, $damage);
        return max(0, $damage + $this->target->getDamageReceiving());
    }
}