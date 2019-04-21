<?php

namespace Tests;

use EverCraft\Battle;
use EverCraft\BattleGrid\BattleGrid;
use EverCraft\BattleGrid\CartesianPoint;
use EverCraft\BattleGrid\Terrain;
use EverCraft\Character;
use EverCraft\CombatAction;
use EverCraft\Items\Weapons\Bow;
use EverCraft\Items\Weapons\Longsword;

require __DIR__ . '/../vendor/autoload.php';

class InitiativeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Helper
     */
    protected $helper;

    public function setUp(): void
    {
        parent::setUp();
        $this->helper = new Helper();
    }

    public function test_simulate_battle_round()
    {
        $battle      = new Battle();
        $battle_grid = new BattleGrid();
        $battle_grid->setDimensions(30, 9);
        $battle->setBattleGrid($battle_grid);
        $player1 = new Character();
        $player2 = new Character();
        $player3 = new Character();
        $player3->use(new Bow());
        $battle_grid->place($player1, new CartesianPoint(2, 2));
        $battle_grid->place($player2, new CartesianPoint(2, 3));
        $battle_grid->place($player3, new CartesianPoint(5, 5));

        $round1 = $battle->initiateNewRound();
        $player1_action = new CombatAction(CombatAction::ATTACK);
        $player1_action->setUpAttack($player1, $player2, 11, $battle_grid); // TODO test for an exception throwing
        $round1->declareAction($player1_action, 20);
        $player2_action = new CombatAction(CombatAction::MOVE);
        $player2_action->setUpMovement($player2, $battle_grid, [new CartesianPoint(6,3),new CartesianPoint(6,6)]);
        $round1->declareAction($player2_action, 10);
        $player3_action = new CombatAction(CombatAction::ATTACK);
        $player3_action->setUpAttack($player3, $player1, 11, $battle_grid);
        $round1->declareAction($player3_action, 18);

        $round1->begin();
        while (!$round1->finished()) {
            $round1->performNextAction();
        }
        $this->helper->assert_has_remaining_hp(4, $player2);
        $this->helper->assert_has_remaining_hp(0, $player1); // TODO if player dies before his initiative, he can't perform the action
        $this->assertEquals($player2, $battle_grid->getCharacterPositions()[6][6]);
    }
}