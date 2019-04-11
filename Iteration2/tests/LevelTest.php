<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Character;

class LevelTest extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_after_1000_experience_points_the_character_gains_a_level()
    {
        $this->character->addXp(500);
        $this->assertEquals(1, $this->character->getLevel());
        $this->character->addXp(500);
        $this->assertEquals(2, $this->character->getLevel());
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getLevel());
    }

    public function test_for_each_level_hp_increase_by_5_plus_con_modifier_con_equals_4()
    {
        $this->character->setAbility('constitution', 4);
        $this->character->addXp(1000);
        $this->assertEquals(4, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(6, $this->character->getMaxHp());
    }


    public function test_for_each_level_hp_increase_by_5_plus_con_modifier_con_equals_20()
    {
        $this->character->setAbility('constitution', 20);
        $this->character->addXp(1000);
        $this->assertEquals(20, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(30, $this->character->getMaxHp());
    }

    public function test_for_each_even_level_attack_roll_increases_by_1()
    {
        // Level 2
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
    }

}
