<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\BattleGrid\BattleGrid;
use EverCraft\BattleGrid\CartesianPoint;
use EverCraft\BattleGrid\MovementException;
use EverCraft\BattleGrid\Terrain;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\Races\Race;

class BonusTest extends \PHPUnit\Framework\TestCase
{
    protected $battle_grid;
    protected $player1;
    protected $player2;

    public function setUp(): void
    {
        parent::setUp();
        $this->player1 = new Character();
        $this->player1->setClass(SocialClass::FIGHTER);
        $this->player1->setRace(Race::DWARF);

        $this->player2 = new Character();
        $this->player2->setClass(SocialClass::MONK);
        $this->player2->setRace(Race::ORC);

        // See tests/test_battle_grid.jpg for an image of the field
        $this->battle_grid = new BattleGrid();
        $this->battle_grid->setDimensions(30, 9);
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new CartesianPoint(1, 0), new CartesianPoint(2, 3));
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new CartesianPoint(5, 5), new CartesianPoint(8, 8));
        $this->battle_grid->setTerrainQuality(TERRAIN::DIFFICULT, new CartesianPoint(1, 0), new CartesianPoint(14, 8));
    }

    public function test_character_placing()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(2, 3));

        $this->assertTrue($this->battle_grid->isSpotEmpty(new CartesianPoint(1, 2)));
        $this->assertTrue($this->battle_grid->isSpotEmpty(new CartesianPoint(2, 4)));
        $this->assertFalse($this->battle_grid->isSpotEmpty(new CartesianPoint(2, 2)));
        $this->assertFalse($this->battle_grid->isSpotEmpty(new CartesianPoint(2, 3)));
    }

    public function test_characters_have_movement_speed_20_sq_per_round()
    {
        $character = new Character();
        $this->assertEquals(20, $character->getMovementSpeed());
    }

    public function test_dwarves_have_movement_speed_15_sq_per_round()
    {
        $this->assertEquals(15, $this->player1->getMovementSpeed());
    }

    public function test_halflings_have_movement_speed_15_sq_per_round()
    {
        $character = new Character();
        $character->setRace(Race::HALFLING);
        $this->assertEquals(15, $character->getMovementSpeed());
    }

    public function test_character_can_move_on_the_map()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->player1->move($this->battle_grid, [new CartesianPoint(4, 2), new CartesianPoint(4, 4)]);
        $end_position = $this->player1->getMapPosition();
        $this->assertEquals(4, $end_position->getX());
        $this->assertEquals(4, $end_position->getY());
    }

    public function test_characters_cannot_move_on_a_map_where_they_have_not_been_placed()
    {
        $this->expectException(MovementException::class);
        $this->player1->move($this->battle_grid, [new CartesianPoint(4, 2), new CartesianPoint(4, 4)]);
    }

    public function test_characters_can_only_move_on_the_battle_field_they_have_been_placed()
    {
        $this->expectException(MovementException::class);
        $this->player1->move(new BattleGrid(), [new CartesianPoint(4, 2), new CartesianPoint(4, 4)]);
    }

    public function test_a_character_can_only_move_on_connected_straight_lines()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->expectException(MovementException::class);
        $this->player1->move($this->battle_grid, [new CartesianPoint(4, 3)]);
    }

    public function test_a_character_can_move_on_connected_straight_lines_backwards()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(4, 3));
        $this->player1->move($this->battle_grid, [new CartesianPoint(4, 0)]);
        $end_position = $this->player1->getMapPosition();
        $this->assertEquals(4, $end_position->getX());
        $this->assertEquals(0, $end_position->getY());
    }

    public function test_a_character_cannot_move_through_another_character()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(2, 2));
        $this->battle_grid->place($this->player2, new CartesianPoint(2, 3));
        $this->expectException(MovementException::class);
        $this->player1->move($this->battle_grid, [new CartesianPoint(2, 4)]);
    }

    public function test_characters_cannot_move_more_than_their_speed_on_a_single_move_polygonal_line_example()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(15, 0));
        $this->expectException(MovementException::class);
        $this->player1->move($this->battle_grid, [
            new CartesianPoint(25, 0),
            new CartesianPoint(25, 5),
            new CartesianPoint(27, 5)
        ]);
    }

    public function test_characters_move_on_difficult_terrain_with_half_their_speed()
    {
        $this->battle_grid->place($this->player1, new CartesianPoint(1, 0));
        $this->expectException(MovementException::class);
        $this->player1->move($this->battle_grid, [
            new CartesianPoint(5, 0),
            new CartesianPoint(5, 4)
        ]);
    }
}