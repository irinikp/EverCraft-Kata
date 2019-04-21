<?php

namespace EverCraft;

/**
 * Class Round
 * @package EverCraft
 */
class Round
{
    /**
     * @var array<CombatAction>
     */
    protected $actions;
    /**
     * @var int
     */
    protected $current_action;

    /**
     * Round constructor.
     */
    public function __construct()
    {
        $this->actions = [];
        $this->current_action = -1;
    }

    /**
     * @param CombatAction $player1_action
     * @param int          $initiative
     */
    public function declareAction(CombatAction $player1_action, int $initiative): void
    {
        $this->actions[$initiative] = $player1_action;
    }

    /**
     * @return bool
     */
    public function finished(): bool
    {
        return ($this->current_action >= sizeof($this->actions));
    }

    /**
     *
     */
    public function begin(): void
    {
        $sorted_actions = [];
        $index = 0;
        ksort($this->actions);
        foreach ($this->actions as $initiative=>$action) {
            $sorted_actions[$index++] = $action;
        }
        $this->actions = $sorted_actions;
        $this->current_action = 0;
    }

    /**
     *
     */
    public function performNextAction(): void
    {
        $action = $this->getNextAction();
        $action->perform();
        $this->current_action++;
    }

    /**
     * @return CombatAction
     */
    protected function getNextAction(): CombatAction
    {
        return $this->actions[$this->current_action];
    }
}