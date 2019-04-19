<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\BattleGrid\BattleGrid;
use EverCraft\BattleGrid\Dimension;
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
        $this->battle_grid->setTerrainHeight(TERRAIN::HIGH, new Dimension(1, 0), new Dimension(2, 3));
        $this->battle_grid->setTerrainHeight(TERRAIN::LOW, new Dimension(5, 5), new Dimension(8, 8));
        $this->battle_grid->setTerrainQuality(TERRAIN::DIFFICULT, new Dimension(1, 0), new Dimension(14,8));
    }

    public function test_character_placing()
    {
        $this->battle_grid->place($this->player1, new Dimension(2,2));
        $this->battle_grid->place($this->player2, new Dimension(2,3));

        $this->assertTrue($this->battle_grid->isSpotEmpty(new Dimension(1, 2)));
        $this->assertTrue($this->battle_grid->isSpotEmpty(new Dimension(2, 4)));
        $this->assertFalse($this->battle_grid->isSpotEmpty(new Dimension(2, 2)));
        $this->assertFalse($this->battle_grid->isSpotEmpty(new Dimension(2, 3)));
    }
}