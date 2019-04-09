<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Abilities;
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
        $hits = $this->createMockAttackRoll(1);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_5()
    {
        $hits = $this->createMockAttackRoll(5);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_10()
    {
        $hits = $this->createMockAttackRoll(10);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_15()
    {
        $hits = $this->createMockAttackRoll(15);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_20()
    {
        $hits = $this->createMockAttackRoll(20);
        $this->assertTrue($hits);
    }

    private function createMockAttackRoll($dice, $target = null, $strength_modifier = 0)
    {
        $character = \Mockery::mock(Character::class);
        $character->shouldReceive('roll')->withArgs([20])->once()->andReturn($dice);
        $character->shouldReceive('getAbilityModifier')->withArgs(['strength'])->once()->andReturn($strength_modifier);

        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($character, $target);
        return $action->attackRoll();
    }

    public function test_when_attack_is_successful_other_character_takes_1_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->createMockAttackRoll(15, $target, 0);

        $this->assertEquals(4, $target->getHp());
    }

    public function test_when_two_attacks_are_successful_other_character_takes_2_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->createMockAttackRoll(15, $target, 0);
        $this->createMockAttackRoll(15, $target, 0);
        $this->assertEquals(3, $target->getHp());
    }

    public function test_when_attack_is_unsuccessful_other_character_does_not_take_damage_when_hit()
    {
        $target = new Character();
        $this->createMockAttackRoll(9, $target, 0);
        $this->assertEquals(5, $target->getHp());
    }

    public function test_if_a_roll_is_a_20_then_a_critical_hit_is_dealt_and_the_damage_is_doubled()
    {
        $target = new Character();
        $this->createMockAttackRoll(20, $target, 0);

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
        $this->character->getAbilities()->setStrength(0);
        $this->assertNotEquals(0, $this->character->getAbilities()->getStrength());
        $this->character->getAbilities()->setStrength(1);
        $this->assertEquals(1, $this->character->getAbilities()->getStrength());
        $this->character->getAbilities()->setStrength(20);
        $this->assertEquals(20, $this->character->getAbilities()->getStrength());
        $this->character->getAbilities()->setStrength(21);
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
        $hits = $this->createMockAttackRoll(13, null, -4);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll14()
    {
        $hits = $this->createMockAttackRoll(14, null, -4);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll6()
    {
        $hits = $this->createMockAttackRoll(6, null, 3);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll7()
    {
        $hits = $this->createMockAttackRoll(7, null, 3);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_damage_scenario_str3_roll14()
    {
        $target = new Character();
        $this->createMockAttackRoll(14, $target, -4);
        $this->assertEquals(4, $target->getHp());
    }

    public function test_add_strength_modifier_to_damage_scenario_str17_roll7()
    {
        $target = new Character();
        $this->createMockAttackRoll(7, $target, 3);
        $this->assertEquals(1, $target->getHp());
    }

    public function test_double_strength_modifier_to_critical_hits()
    {
        $target = new Character();
        $this->createMockAttackRoll(20, $target, 1);
        $this->assertEquals(1, $target->getHp());
    }
}
