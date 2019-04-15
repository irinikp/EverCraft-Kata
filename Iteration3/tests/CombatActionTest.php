<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\CombatAction;

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

        $this->assert_has_remaining_hp(4, $target);
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_when_two_attacks_are_successful_other_character_takes_2_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->createAttackRoll(15, $target, 0);
        $this->createAttackRoll(15, $target, 0);
        $this->assert_has_remaining_hp(3, $target);
    }

    public function test_when_attack_is_unsuccessful_other_character_does_not_take_damage_when_hit()
    {
        $target = new Character();
        $this->createAttackRoll(9, $target, 0);
        $this->assert_has_remaining_hp(5, $target);
    }

    public function test_if_a_roll_is_a_20_then_a_critical_hit_is_dealt_and_the_damage_is_doubled()
    {
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(3, $target);
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
        $this->character->setAbility(Abilities::STR, 3);
        $hits = $this->createAttackRoll(13, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll14()
    {
        $this->character->setAbility(Abilities::STR, 3);
        $hits = $this->createAttackRoll(14, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll6()
    {
        $this->character->setAbility(Abilities::STR, 17);
        $hits = $this->createAttackRoll(6, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll7()
    {
        $this->character->setAbility(Abilities::STR, 17);
        $hits = $this->createAttackRoll(7, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_damage_scenario_str3_roll14()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 3);
        $this->createAttackRoll(14, $target);
        $this->assert_has_remaining_hp(4, $target);
    }

    public function test_add_strength_modifier_to_damage_scenario_str17_roll7()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 17);
        $this->createAttackRoll(7, $target, 3);
        $this->assert_has_remaining_hp(1, $target);
    }

    public function test_double_strength_modifier_to_critical_hits()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 12);
        $this->createAttackRoll(20, $target);
        $this->assert_has_remaining_hp(1, $target);
    }

    public function test_minimum_damage_is_always_1_even_on_a_critical_hit()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 2);
        $this->createAttackRoll(20, $target);
        $this->assert_has_remaining_hp(4, $target);
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

    public function test_add_dexterity_modifier_to_armor_class_of_target()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::STR, 2);
        $this->assert_attacker_hits_with_roll(16, $target);
    }

    public function test_rogues_critical_hit_is_dealt_and_the_damage_is_tripled()
    {
        $this->character->setClass(SocialClass::ROGUE);
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(2, $target);
    }

    public function test_rogues_strength_modifier_does_not_apply_to_attack()
    {
        $this->character->setAbility(Abilities::STR, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->assert_attacker_hits_with_roll(10, new Character());
    }

    public function test_rogues_dexterity_modifier_applies_to_attack()
    {
        $this->character->setAbility(Abilities::DEX, 12);
        $this->character->setClass(SocialClass::ROGUE);
        $this->assert_attacker_hits_with_roll(9, new Character());
    }

    public function test_dexterity_modifier_of_target_is_ignored_if_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::DEX, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->assert_attacker_hits_with_roll(14, $target);
    }

    public function test_dexterity_modifier_of_target_is_not_ignored_if_not_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 6);
        $this->character->setAbility(Abilities::DEX, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->assert_attacker_hits_with_roll(12, $target);
    }

    public function test_when_monk_attack_is_successful_other_character_takes_3_points_of_damage_when_hit()
    {
        $this->character->setClass(SocialClass::MONK);
        $target = new Character();
        $this->createAttackRoll(15, $target, 0);
        $this->assert_has_remaining_hp(2, $target);
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_paladin_plus_2_to_attack_when_attacking_evil_characters()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->assert_attacker_hits_with_roll(10, $target);

        $target->setAlignment(Alignment::EVIL);
        $this->assert_attacker_hits_with_roll(8, $target);
    }

    public function test_paladin_plus_2_to_damage_when_attacking_evil_characters()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->createAttackRoll(10, $target);
        $this->assert_has_remaining_hp(4, $target);

        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->createAttackRoll(8, $target);
        $this->assert_has_remaining_hp(2, $target);
    }

    public function test_paladins_critical_hit_is_dealt_and_the_damage_is_tripled_when_target_is_evil()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(3, $target);

        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(-4, $target);
        $this->assertTrue($target->isDead());
    }

    /**
     * @param int       $dice
     * @param Character $defender
     */
    private function assert_defender_hits_with_roll($dice, $defender): void
    {
        $hits = $this->createCounterAttackRoll($dice - 1, $defender);
        $this->assertFalse($hits);
        $hits = $this->createCounterAttackRoll($dice, $defender);
        $this->assertTrue($hits);
    }

    /**
     * @param int       $hp
     * @param Character $character
     */
    private function assert_has_remaining_hp($hp, $character): void
    {
        $this->assertEquals($hp, $character->getHp());
    }

    /**
     * @param int       $dice
     * @param Character $target
     */
    private function assert_attacker_hits_with_roll($dice, $target): void
    {
        $hits = $this->createAttackRoll($dice - 1, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll($dice, $target);
        $this->assertTrue($hits);
    }

    /**
     * @param int            $dice
     * @param Character|null $target
     *
     * @return bool
     */
    private function createAttackRoll($dice, $target = null): bool
    {
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($this->character, $target, $dice);
        return $action->attackRoll();
    }

    /**
     * @param int       $dice
     * @param Character $target
     *
     * @return bool
     */
    private function createCounterAttackRoll($dice, $target): bool
    {
        $action = new CombatAction($target, $this->character, $dice);
        return $action->attackRoll();
    }
}
