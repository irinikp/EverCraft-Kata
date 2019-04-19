<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\BattleGrid\BattleGrid;
use EverCraft\BattleGrid\Dimensions;
use EverCraft\BattleGrid\Terrain;
use EverCraft\Character;

class BonusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Character
     */
    protected $character;
    protected $battle_grid;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
        $this->battle_grid = new BattleGrid();
        $this->battle_grid->setDimensions(20, 20);
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
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new Dimensions(0, 0), new Dimensions(2, 3));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new Dimensions(0, 0)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new Dimensions(0, 1)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new Dimensions(1, 2)));
        $this->assertEquals(TERRAIN::HIGH, $this->battle_grid->getTerrainHeight(new Dimensions(2, 3)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new Dimensions(3, 3)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new Dimensions(5, 5)));
    }

    public function test_low_battle_terrain()
    {
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new Dimensions(5, 5), new Dimensions(6, 6));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new Dimensions(5, 5)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new Dimensions(5, 6)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new Dimensions(6, 5)));
        $this->assertEquals(TERRAIN::LOW, $this->battle_grid->getTerrainHeight(new Dimensions(5, 6)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainHeight(new Dimensions(5, 7)));
    }

    public function test_difficult_terrain()
    {
        $this->battle_grid->setTerrainQuality(TERRAIN::DIFFICULT, new Dimensions(5, 5), new Dimensions(6, 6));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new Dimensions(5, 5)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new Dimensions(5, 6)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new Dimensions(6, 5)));
        $this->assertEquals(TERRAIN::DIFFICULT, $this->battle_grid->getTerrainQuality(new Dimensions(5, 6)));
        $this->assertEquals(TERRAIN::NORMAL, $this->battle_grid->getTerrainQuality(new Dimensions(5, 7)));
    }

    public function test_bounds()
    {
        $this->expectException(\OutOfBoundsException::class);
        $this->battle_grid->getTerrainHeight(new Dimensions(21, 0));

    }
}