<?php

namespace EverCraft;

use EverCraft\BattleGrid\BattleGrid;

/**
 * Class Battle
 * @package EverCraft
 */
class Battle
{
    /**
     * @var BattleGrid
     */
    protected $battle_grid;
    /**
     * @var array<Round>
     */
    protected $rounds;
    /**
     * @var int
     */
    protected $current_round;

    /**
     * @return int
     */
    public function getCurrentRoundNumber(): int
    {
        return $this->current_round;
    }

    /**
     * @param int $current_round
     */
    public function setCurrentRoundNumber(int $current_round): void
    {
        $this->current_round = $current_round;
    }

    /**
     * @return Round|null
     */
    public function getCurrentRound(): ?Round
    {
        if ($this->current_round >= 0) {
            return $this->rounds[$this->current_round];
        } else {
            return null;
        }
    }

    /**
     * Battle constructor.
     */
    public function __construct()
    {
        $this->rounds        = [];
        $this->current_round = -1;
    }

    /**
     * @return BattleGrid
     */
    public function getBattleGrid(): BattleGrid
    {
        return $this->battle_grid;
    }

    /**
     * @param BattleGrid $battle_grid
     */
    public function setBattleGrid(BattleGrid $battle_grid): void
    {
        $this->battle_grid = $battle_grid;
    }

    /**
     * @return array
     */
    public function getRounds(): array
    {
        return $this->rounds;
    }

    /**
     * @param array $rounds
     */
    public function setRounds(array $rounds): void
    {
        $this->rounds = $rounds;
    }

    /**
     * @return Round
     */
    public function initiateNewRound(): Round
    {
        $this->current_round++;
        $this->rounds[$this->current_round] = new Round();
        return $this->getCurrentRound();
    }
}