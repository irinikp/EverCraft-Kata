<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Character;
use Dnd\CombatAction;

class CombatActionTest extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_attack_roll_1()
    {
        $hits = $this->createAttackRoll(1);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_5()
    {
        $hits = $this->createAttackRoll(5);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_10()
    {
        $hits = $this->createAttackRoll(10);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_15()
    {
        $hits = $this->createAttackRoll(15);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_20()
    {
        $hits = $this->createAttackRoll(20);
        $this->assertTrue($hits);
    }

    public function test_when_attack_is_successful_other_character_takes_1_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->createAttackRoll(15, $target, 0);

        $this->assertEquals(4, $target->getHp());
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_when_two_attacks_are_successful_other_character_takes_2_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->createAttackRoll(15, $target, 0);
        $this->createAttackRoll(15, $target, 0);
        $this->assertEquals(3, $target->getHp());
    }

    public function test_when_attack_is_unsuccessful_other_character_does_not_take_damage_when_hit()
    {
        $target = new Character();
        $this->createAttackRoll(9, $target, 0);
        $this->assertEquals(5, $target->getHp());
    }

    public function test_if_a_roll_is_a_20_then_a_critical_hit_is_dealt_and_the_damage_is_doubled()
    {
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);

        $this->assertEquals(3, $target->getHp());
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

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll13()
    {
        $this->character->setAbility('strength', 3);
        $hits = $this->createAttackRoll(13, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll14()
    {
        $this->character->setAbility('strength', 3);
        $hits = $this->createAttackRoll(14, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll6()
    {
        $this->character->setAbility('strength', 17);
        $hits = $this->createAttackRoll(6, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll7()
    {
        $this->character->setAbility('strength', 17);
        $hits = $this->createAttackRoll(7, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_damage_scenario_str3_roll14()
    {
        $target = new Character();
        $this->character->setAbility('strength', 3);
        $this->createAttackRoll(14, $target);
        $this->assertEquals(4, $target->getHp());
    }

    public function test_add_strength_modifier_to_damage_scenario_str17_roll7()
    {
        $target = new Character();
        $this->character->setAbility('strength', 17);
        $this->createAttackRoll(7, $target, 3);
        $this->assertEquals(1, $target->getHp());
    }

    public function test_double_strength_modifier_to_critical_hits()
    {
        $target = new Character();
        $this->character->setAbility('strength', 12);
        $this->createAttackRoll(20, $target);
        $this->assertEquals(1, $target->getHp());
    }

    public function test_minimum_damage_is_always_1_even_on_a_critical_hit()
    {
        $target = new Character();
        $this->character->setAbility('strength', 2);
        $this->createAttackRoll(20, $target);
        $this->assertEquals(4, $target->getHp());
    }

    public function test_when_a_successful_attack_occurs_the_character_gains_10_experience_points()
    {
        $this->createAttackRoll(10);
        $this->assertEquals(10, $this->character->getXp());
        $this->assertTrue(true);
    }

    public function test_compute_attack_roll_when_attacking()
    {
        $this->character->addXp(3000);
        $hits = $this->createAttackRoll(8, null);
        $this->assertTrue($hits);

    }

    private function createAttackRoll($dice, $target = null)
    {
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($this->character, $target, $dice);
        return $action->attackRoll();
    }

}
