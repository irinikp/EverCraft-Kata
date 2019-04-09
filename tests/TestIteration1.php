<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Abilities;
use Dnd\Alignment;
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
        $this->assertEquals(5, $this->character->getMaxHp());
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

    public function test_abilities_default_to_10()
    {
        $this->assertEquals(10, $this->character->getAbilities()->getStrength());
        $this->assertEquals(10, $this->character->getAbilities()->getDexterity());
        $this->assertEquals(10, $this->character->getAbilities()->getConstitution());
        $this->assertEquals(10, $this->character->getAbilities()->getIntelligence());
        $this->assertEquals(10, $this->character->getAbilities()->getWisdom());
        $this->assertEquals(10, $this->character->getAbilities()->getCharisma());
    }

    public function test_strength_range_from_1_to_20()
    {
        $this->character->setAbility('strength', 0);
        $this->assertNotEquals(0, $this->character->getAbilities()->getStrength());
        $this->character->setAbility('strength', 1);
        $this->assertEquals(1, $this->character->getAbilities()->getStrength());
        $this->character->setAbility('strength', 20);
        $this->assertEquals(20, $this->character->getAbilities()->getStrength());
        $this->character->setAbility('strength', 21);
        $this->assertNotEquals(21, $this->character->getAbilities()->getStrength());
    }

    public function test_abilities_modifier()
    {
        $this->assertEquals(-5, Abilities::getModifier(1));
        $this->assertEquals(-4, Abilities::getModifier(2));
        $this->assertEquals(-4, Abilities::getModifier(3));
        $this->assertEquals(-3, Abilities::getModifier(4));
        $this->assertEquals(-3, Abilities::getModifier(5));
        $this->assertEquals(-2, Abilities::getModifier(6));
        $this->assertEquals(-2, Abilities::getModifier(7));
        $this->assertEquals(-1, Abilities::getModifier(8));
        $this->assertEquals(-1, Abilities::getModifier(9));
        $this->assertEquals(0, Abilities::getModifier(10));
        $this->assertEquals(0, Abilities::getModifier(11));
        $this->assertEquals(1, Abilities::getModifier(12));
        $this->assertEquals(1, Abilities::getModifier(13));
        $this->assertEquals(2, Abilities::getModifier(14));
        $this->assertEquals(2, Abilities::getModifier(15));
        $this->assertEquals(3, Abilities::getModifier(16));
        $this->assertEquals(3, Abilities::getModifier(17));
        $this->assertEquals(4, Abilities::getModifier(18));
        $this->assertEquals(4, Abilities::getModifier(19));
        $this->assertEquals(5, Abilities::getModifier(20));
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

    public function test_add_dexterity_modifier_to_armor_class()
    {
        $this->character->setAbility('dexterity', 15);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_add_constitution_modifier_to_hit_points()
    {
        $this->character->setAbility('constitution', 15);
        $this->assertEquals(7, $this->character->getHp());
        $this->assertEquals(7, $this->character->getMaxHp());
    }

    public function test_add_constitution_modifier_to_hit_points_always_at_least_1_hp()
    {
        $this->character->setAbility('constitution', 1);
        $this->assertEquals(1, $this->character->getHp());
        $this->assertEquals(1, $this->character->getMaxHp());
    }

    public function test_a_new_character_has_zero_xp()
    {
        $this->assertEquals(0, $this->character->getXp());
    }

    public function test_when_a_successful_attack_occurs_the_character_gains_10_experience_points()
    {
        $this->createAttackRoll(10);
        $this->assertEquals(10, $this->character->getXp());
        $this->assertTrue(true);
    }

    public function test_default_level()
    {
        $this->assertEquals(1, $this->character->getLevel());
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

    private function createAttackRoll($dice, $target = null)
    {
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($this->character, $target, $dice);
        return $action->attackRoll();
    }

}
