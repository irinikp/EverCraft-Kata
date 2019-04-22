<?php

namespace Tests;

use EverCraft\Map\BattleGrid;
use EverCraft\Map\CartesianPoint;
use EverCraft\Character;
use EverCraft\Items\Weapons\Bow;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\NunChucks;
use EverCraft\Items\Weapons\Waraxe;

require __DIR__ . '/../vendor/autoload.php';

class RangeTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Character
     */
    protected $player1;
    /**
     * @var Character
     */
    protected $player2;
    /**
     * @var BattleGrid
     */
    protected $battle_grid;

    public function setUp(): void
    {
        parent::setUp();
        $this->player1     = new Character();
        $this->player2     = new Character();
        $this->battle_grid = new BattleGrid();
        $this->battle_grid->setDimensions(30, 9);
    }

    public function test_non_missile_weapon_range_is_1()
    {
        $this->assertEquals(1, (new Longsword())->getMinRange());
        $this->assertEquals(1, (new Longsword())->getMaxRange());
        $this->assertEquals(1, (new NunChucks())->getMinRange());
        $this->assertEquals(1, (new NunChucks())->getMaxRange());
        $this->assertEquals(1, (new Waraxe())->getMinRange());
        $this->assertEquals(1, (new Waraxe())->getMaxRange());
    }

    public function test_missile_weapon_min_range_is_2()
    {
        $this->assertEquals(2, (new Bow())->getMinRange());
    }

    public function test_missile_weapon_max_range_is_20()
    {
        $this->assertEquals(20, (new Bow())->getMaxRange());
    }

    public function test_character_can_hit_adjacent_character_with_bear_hands()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(2, 3));
        $this->assertTrue($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_can_hit_diagonally_adjacent_character_with_bear_hands()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(3, 3));
        $this->assertTrue($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_cannot_hit_non_adjacent_character_with_bear_hands()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(4, 3));
        $this->assertFalse($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_can_hit_adjacent_character_with_non_missile()
    {
        $this->player1->use(new Longsword());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(2, 3));
        $this->assertTrue($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_can_hit_diagonally_adjacent_character_with_non_missile()
    {
        $this->player1->use(new Waraxe());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(3, 3));
        $this->assertTrue($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_cannot_hit_non_adjacent_character_with_non_missile()
    {
        $this->player1->use(new Longsword());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(4, 3));
        $this->assertFalse($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_cannot_hit_adjacent_character_with_missile()
    {
        $this->player1->use(new Bow());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(2, 3));
        $this->assertFalse($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_cannot_hit_diagonally_adjacent_character_with_missile()
    {
        $this->player1->use(new Bow());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(3, 3));
        $this->assertFalse($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_can_hit_within_range_character_with_missile()
    {
        $this->player1->use(new Bow());
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(5, 6));
        $this->assertTrue($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }

    public function test_character_cannot_hit_out_of_range_character_with_missile()
    {
        $this->player1->use(new Bow());
        $this->battle_grid->place($this->player1, new CartesianPoint(0, 0));
        $this->battle_grid->place($this->player2, new CartesianPoint(25, 0));
        $this->assertFalse($this->battle_grid->isTargetInRange($this->player1, $this->player2));
    }
}