<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Alignment;
use Dnd\Battle;
use Dnd\Character;
use Dnd\CombatAction;

class TestIteration1 extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_get_set_name()
    {
        $this->assertEquals('Bilbur', $this->character->getName());
    }

    public function test_get_set_alignment()
    {
        $this->character->setAlignment(Alignment::GOOD);
        $this->assertEquals(Alignment::GOOD, $this->character->getAlignment());
    }

    public function test_alignments_default_values()
    {
        $this->character->setAlignment('innocent');
        $this->assertNotEquals('innocent', $this->character->getAlignment());
    }

    public function test_ac()
    {
        $this->assertEquals(10, $this->character->getAc());
    }

    public function test_hp()
    {
        $this->assertEquals(5, $this->character->getHp());
    }

    public function test_roll_dice()
    {
        for ($i = 1; $i <= 20; $i++) {
            $dice = $this->character->roll($i);
            $this->assertGreaterThanOrEqual(1, $dice);
            $this->assertLessThanOrEqual($i, $dice);
        }
    }

    public function test_attack_roll_1()
    {
        $hits = $this->create_mock_attack_roll(1);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_5()
    {
        $hits = $this->create_mock_attack_roll(5);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_10()
    {
        $hits = $this->create_mock_attack_roll(10);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_15()
    {
        $hits = $this->create_mock_attack_roll(15);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_20()
    {
        $hits = $this->create_mock_attack_roll(20);
        $this->assertTrue($hits);
    }

    private function create_mock_attack_roll($dice, $target = null)
    {
        $character = \Mockery::mock(Character::class);
        $character->shouldReceive('roll')->withArgs([20])->once()->andReturn($dice);
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($character, $target);
        return $action->attackRoll();
    }

    public function test_when_attack_is_successful_other_character_takes_1_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->create_mock_attack_roll(15, $target);

        $this->assertEquals(4, $target->getHp());
    }

    public function test_when_two_attacks_are_successful_other_character_takes_2_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->create_mock_attack_roll(15, $target);
        $this->create_mock_attack_roll(15, $target);
        $this->assertEquals(3, $target->getHp());
    }

    public function test_when_attack_is_unsuccessful_other_character_does_not_take_damage_when_hit()
    {
        $target = new Character();
        $this->create_mock_attack_roll(9, $target);
        $this->assertEquals(5, $target->getHp());
    }

    public function test_if_a_roll_is_a_20_then_a_critical_hit_is_dealt_and_the_damage_is_doubled()
    {
        $target = new Character();
        $this->create_mock_attack_roll(20, $target);

        $this->assertEquals(3, $target->getHp());
    }

    public function test_new_character_is_alive()
    {
        $this->assertFalse($this->character->isDead());
    }

    public function test_when_hit_points_are_0_or_fewer_the_character_is_dead()
    {
        $this->character->setHp(0);
        $this->assertTrue($this->character->isDead());
    }

    public function test_when_hit_points_increase_after_0_the_character_is_alive_again()
    {
        $this->character->setHp(0);
        $this->character->setHp(1);
        $this->assertFalse($this->character->isDead());

    }

}
