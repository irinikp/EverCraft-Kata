<?php

namespace Tests;

use Dnd\Character;

require __DIR__ . '/../vendor/autoload.php';

class IterationTest extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_fighter_attack_roll_increases_by_1_in_every_level()
    {
        $this->character->setClass('fighter');
        // Level 2
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getAttackRoll());
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getAttackRoll());
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getAttackRoll());

    }
}