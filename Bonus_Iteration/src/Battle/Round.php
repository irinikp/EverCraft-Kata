<?php

namespace EverCraft\Battle;

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
        $this->actions        = [];
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
        $index          = 0;
        krsort($this->actions, SORT_NUMERIC);
        foreach ($this->actions as $initiative => $action) {
            $sorted_actions[$index++] = $action;
        }
        $this->actions        = $sorted_actions;
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
     * @return array
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * @param array $actions
     */
    public function setActions(array $actions): void
    {
        $this->actions = $actions;
    }

    /**
     * @return CombatAction
     */
    protected function getNextAction(): CombatAction
    {
        return $this->actions[$this->current_action];
    }
}