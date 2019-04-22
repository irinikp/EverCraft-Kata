<?php

namespace Tests;

use EverCraft\Battle\Battle;
use EverCraft\Battle\CombatAction;
use EverCraft\Battle\Round;
use EverCraft\Character;
use EverCraft\Items\Weapons\Bow;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Map\BattleGrid;
use EverCraft\Map\CartesianPoint;

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
        $battle      = $this->initiateBattle();
        $battle_grid = $battle->getBattleGrid();
        $player1     = new Character();
        $player2     = new Character();
        $player3     = new Character();
        $round1      = $this->setUpTestBattlePlayersAndTheirActions($battle, $player1, $player2, $player3);

        $round1->begin();
        while (!$round1->isCompleted()) {
            $round1->performNextAction();
        }
        $this->helper->assert_has_remaining_hp(4, $player2);
        $this->helper->assert_has_remaining_hp(0, $player1);
        $this->assertEquals($player2, $battle_grid->getCharacterPositions()[6][6]);
        $this->assertNotEquals($player2, $battle_grid->getCharacterPositions()[2][3]);
    }

    public function test_actions_on_each_round_go_based_on_initiative_biggest_first()
    {
        $battle  = $this->initiateBattle();
        $player1 = new Character();
        $player2 = new Character();
        $player3 = new Character();

        $round1 = $this->setUpTestBattlePlayersAndTheirActions($battle, $player1, $player2, $player3);

        $round1->begin();
        $round1_actions = $round1->getActions();
        $this->assertEquals('1', $round1_actions[0]->getPlayer()->getName());
        $this->assertEquals('3', $round1_actions[1]->getPlayer()->getName());
        $this->assertEquals('2', $round1_actions[2]->getPlayer()->getName());
    }

    public function test_round_initiating()
    {
        $battle = $this->initiateBattle();
        $round  = null;
        $this->assertEquals($round, $battle->getCurrentRound());
        for ($i = 0; $i < 3; ++$i) {
            $round = $battle->initiateNewRound();
        }
        $this->assertEquals(2, $battle->getCurrentRoundNumber());
        $this->assertEquals($round, $battle->getCurrentRound());
    }

    public function test_players_cannot_make_an_attack_on_a_movement_action()
    {
        $battle      = $this->initiateBattle();
        $battle_grid = $battle->getBattleGrid();
        $player1     = new Character();
        $player2     = new Character();
        $battle_grid->place($player1, new CartesianPoint(2, 2));
        $battle_grid->place($player2, new CartesianPoint(2, 3));
        $player1_action = new CombatAction(CombatAction::MOVE);
        $this->expectException(\Exception::class);
        $player1_action->setUpAttack($player1, $player2, 11, $battle_grid);
    }

    public function test_players_cannot_make_a_movement_on_an_attack_action()
    {
        $battle      = $this->initiateBattle();
        $battle_grid = $battle->getBattleGrid();
        $player1     = new Character();
        $battle_grid->place($player1, new CartesianPoint(2, 2));
        $player1_action = new CombatAction(CombatAction::ATTACK);
        $this->expectException(\Exception::class);
        $player1_action->setUpMovement($player1, $battle_grid, [new CartesianPoint(6, 3), new CartesianPoint(6, 6)]);
    }

    public function test_if_players_die_before_initiative_they_do_not_perform_their_action()
    {
        $battle      = $this->initiateBattle();
        $battle_grid = $battle->getBattleGrid();
        $player1     = new Character();
        $player2     = new Character();
        $player1->use(new Longsword());
        $battle_grid->place($player1, new CartesianPoint(2, 2));
        $battle_grid->place($player2, new CartesianPoint(2, 3));

        $round1         = $battle->initiateNewRound();
        $player1_action = new CombatAction(CombatAction::ATTACK);
        $player1_action->setUpAttack($player1, $player2, 11, $battle_grid);
        $round1->declareAction($player1_action, 20);
        $player2_action = new CombatAction(CombatAction::MOVE);
        $player2_action->setUpMovement($player2, $battle_grid, [new CartesianPoint(6, 3), new CartesianPoint(6, 6)]);
        $round1->declareAction($player2_action, 10);

        $round1->begin();
        while (!$round1->isCompleted()) {
            $round1->performNextAction();
        }
        $this->helper->assert_has_remaining_hp(0, $player2);
        $this->assertEquals($player2, $battle_grid->getCharacterPositions()[2][3]);
    }

    /**
     * @param Battle    $battle
     * @param Character $player1
     * @param Character $player2
     * @param Character $player3
     *
     * @return Round
     * @throws \Exception
     */
    protected function setUpTestBattlePlayersAndTheirActions($battle, $player1, $player2, $player3): Round
    {
        $battle_grid = $battle->getBattleGrid();
        $player1->setName('1');
        $player2->setName('2');
        $player3->setName('3');
        $player3->use(new Bow());
        $battle_grid->place($player1, new CartesianPoint(2, 2));
        $battle_grid->place($player2, new CartesianPoint(2, 3));
        $battle_grid->place($player3, new CartesianPoint(5, 5));

        $round1         = $battle->initiateNewRound();
        $player1_action = new CombatAction(CombatAction::ATTACK);
        $player1_action->setUpAttack($player1, $player2, 11, $battle_grid);
        $round1->declareAction($player1_action, 20);
        $player2_action = new CombatAction(CombatAction::MOVE);
        $player2_action->setUpMovement($player2, $battle_grid, [new CartesianPoint(6, 3), new CartesianPoint(6, 6)]);
        $round1->declareAction($player2_action, 10);
        $player3_action = new CombatAction(CombatAction::ATTACK);
        $player3_action->setUpAttack($player3, $player1, 11, $battle_grid);
        $round1->declareAction($player3_action, 18);
        return $round1;
    }

    /**
     * @return Battle
     */
    protected function initiateBattle(): Battle
    {
        $battle      = new Battle();
        $battle_grid = new BattleGrid();
        $battle_grid->setDimensions(30, 9);
        $battle->setBattleGrid($battle_grid);
        return $battle;
    }
}