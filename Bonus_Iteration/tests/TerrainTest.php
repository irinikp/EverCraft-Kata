<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\BattleGrid\BattleGrid;
use EverCraft\BattleGrid\CartesianPoint;
use EverCraft\BattleGrid\Terrain;
use EverCraft\Character;
use EverCraft\CombatAction;

class TerrainTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Character
     */
    protected $character;
    /**
     * @var BattleGrid
     */
    protected $battle_grid;
    /**
     * @var Helper
     */
    protected $helper;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
        $this->battle_grid = new BattleGrid();
        $this->battle_grid->setDimensions(20, 20);
        $this->helper = new Helper();
    }

    public function test_battle_grid_dimensions()
    {
        $battle_grid = new BattleGrid();
        $battle_grid->setDimensions(20, 20);
        $this->assertEquals(20, $battle_grid->getDimensions()->getX());
        $this->assertEquals(20, $battle_grid->getDimensions()->getY());
    }

    public function test_high_battle_terrain()
    {
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new CartesianPoint(0, 0), new CartesianPoint(2, 3));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new CartesianPoint(0, 0)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new CartesianPoint(0, 1)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new CartesianPoint(1, 2)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new CartesianPoint(2, 3)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new CartesianPoint(3, 3)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new CartesianPoint(5, 5)));
    }

    public function test_low_battle_terrain()
    {
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new CartesianPoint(5, 5), new CartesianPoint(6, 6));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new CartesianPoint(5, 5)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new CartesianPoint(5, 6)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new CartesianPoint(6, 5)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new CartesianPoint(5, 6)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new CartesianPoint(5, 7)));
    }

    public function test_difficult_terrain()
    {
        $this->battle_grid->setTerrainQuality(TERRAIN::DIFFICULT, new CartesianPoint(5, 5), new CartesianPoint(6, 6));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new CartesianPoint(5, 5)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new CartesianPoint(5, 6)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new CartesianPoint(6, 5)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new CartesianPoint(5, 6)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainQuality(new CartesianPoint(5, 7)));
    }

    public function test_bounds()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->battle_grid->getTerrainHeight(new CartesianPoint(20, 0));
        $battle_grid = new BattleGrid();
        $this->expectException(\OutOfBoundsException::class);
        $battle_grid->getTerrainHeight(new CartesianPoint(0, 0));
    }

    public function test_higher_terrain_gives_the_attacker_plus_1_to_attack_roll()
    {
        $target = new Character();
        $this->battle_grid->place($this->character, new CartesianPoint(2, 2));
        $this->battle_grid->place($target, new CartesianPoint(2, 3));
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new CartesianPoint(2,2), new CartesianPoint(2, 2));
        $this->assertFalse($this->battle_grid->attack($this->character, $target, 8));
        $this->assertTrue($this->battle_grid->attack($this->character, $target, 9));
    }

    public function test_lower_terrain_gives_the_attacker_minus_1_to_attack_roll()
    {
        $target = new Character();
        $this->battle_grid->place($this->character, new CartesianPoint(2, 2));
        $this->battle_grid->place($target, new CartesianPoint(2, 3));
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new CartesianPoint(2,2), new CartesianPoint(2, 2));
        $this->assertFalse($this->battle_grid->attack($this->character, $target, 10));
        $this->assertTrue($this->battle_grid->attack($this->character, $target, 11));
    }

    public function test_high_terrain_gives_the_attacker_plus_2_to_attack_roll_when_target_is_on_low_terrain()
    {
        $target = new Character();
        $this->battle_grid->place($this->character, new CartesianPoint(2, 2));
        $this->battle_grid->place($target, new CartesianPoint(2, 3));
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new CartesianPoint(2,2), new CartesianPoint(2, 2));
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new CartesianPoint(2,3), new CartesianPoint(2, 3));
        $this->assertFalse($this->battle_grid->attack($this->character, $target, 7));
        $this->assertTrue($this->battle_grid->attack($this->character, $target, 8));
    }
}