<?php

namespace Tests;

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\InvalidAlignmentException;

require __DIR__ . '/../vendor/autoload.php';

class ClassesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Character
     */
    protected $character;
    /**
     * @var Helper
     */
    protected $helper;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
        $this->helper = new Helper();
    }

    public function test_fighter_attack_roll_increases_by_1_in_every_level()
    {
        $this->character->setClass(SocialClass::FIGHTER);
        // Level 2
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll($this->character->getLevel(), $this->character));
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll($this->character->getLevel(), $this->character));
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll($this->character->getLevel(), $this->character));
    }

    public function test_fighter_hp_is_10_initially()
    {
        $this->character->setClass(SocialClass::FIGHTER);
        $this->assertEquals(10, $this->character->getHp());
        $this->assertEquals(10, $this->character->getMaxHp());
    }

    public function test_fighter_for_each_level_hp_increase_by_10_plus_con_modifier_equals_4()
    {
        $this->character->setClass(SocialClass::FIGHTER);
        $this->character->setAbility(Abilities::CON, 4);
        $this->character->addXp(1000);
        $this->assertEquals(14, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(21, $this->character->getMaxHp());
    }


    public function test_fighter_for_each_level_hp_increase_by_10_plus_con_modifier_equals_20()
    {
        $this->character->setClass(SocialClass::FIGHTER);
        $this->character->setAbility(Abilities::CON, 20);
        $this->character->addXp(1000);
        $this->assertEquals(30, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(45, $this->character->getMaxHp());
    }

    public function test_rogue_can_not_have_alignment_good()
    {
        $this->character->setAlignment(Alignment::GOOD);
        $this->character->setClass(SocialClass::ROGUE);
        $this->assertNotEquals(Alignment::GOOD, $this->character->getAlignment());
        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment(Alignment::GOOD);
    }

    public function test_monk_hp_is_6_initially()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->assertEquals(6, $this->character->getHp());
        $this->assertEquals(6, $this->character->getMaxHp());
    }

    public function test_monk_for_each_level_hp_increase_by_6_plus_con_modifier_equals_4()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::CON, 4);
        $this->character->addXp(1000);
        $this->assertEquals(6, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(9, $this->character->getMaxHp());
    }


    public function test_monk_for_each_level_hp_increase_by_10_plus_con_modifier_equals_20()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::CON, 20);
        $this->character->addXp(1000);
        $this->assertEquals(22, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(33, $this->character->getMaxHp());
    }


    public function test_monk_adds_positive_wisdom_and_dexterity_modifier_to_armor_class()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::WIS, 15);
        $this->assertEquals(14, $this->character->getAc());
    }

    public function test_monk_does_not_add_negative_wisdom_modifier_to_armor_class()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::WIS, 4);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_monks_attack_rolls_increases_every_2nd_and_3rd_level()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll(1, $this->character));
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll(2, $this->character));
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll(3, $this->character));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(4, $this->character));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(5, $this->character));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(6, $this->character));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(7, $this->character));
        $this->assertEquals(5, $this->character->getClass()->getAttackRoll(8, $this->character));
        $this->assertEquals(6, $this->character->getClass()->getAttackRoll(9, $this->character));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(10, $this->character));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(11, $this->character));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(12, $this->character));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(13, $this->character));
        $this->assertEquals(9, $this->character->getClass()->getAttackRoll(14, $this->character));
        $this->assertEquals(10, $this->character->getClass()->getAttackRoll(15, $this->character));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(16, $this->character));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(17, $this->character));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(18, $this->character));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(19, $this->character));
        $this->assertEquals(13, $this->character->getClass()->getAttackRoll(20, $this->character));
    }

    public function test_paladin_has_8_hit_points_per_level_instead_of_5()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $this->assertEquals(8, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(16, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(24, $this->character->getMaxHp());
        $this->character->setAbility(Abilities::CON, 18);
        $this->assertEquals(36, $this->character->getMaxHp());
    }

    public function test_paladins_attack_rolls_increases_by_1_for_every_level()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll(1, $this->character));
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll(2, $this->character));
        $this->assertEquals(6, $this->character->getClass()->getAttackRoll(7, $this->character));
        $this->assertEquals(19, $this->character->getClass()->getAttackRoll(20, $this->character));
    }

    public function test_paladin_can_only_have_good_alignment()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $this->assertEquals(Alignment::GOOD, $this->character->getAlignment());

        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment(Alignment::NEUTRAL);

        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment(Alignment::EVIL);
    }

    public function test_rogues_critical_hit_is_dealt_and_the_damage_is_tripled()
    {
        $this->character->setClass(SocialClass::ROGUE);
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(2, $target);
    }

    public function test_rogues_strength_modifier_does_not_apply_to_attack()
    {
        $this->character->setAbility(Abilities::STR, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->helper->assert_attacker_hits_with_roll($this->character, 10, new Character());
    }

    public function test_rogues_dexterity_modifier_applies_to_attack()
    {
        $this->character->setAbility(Abilities::DEX, 12);
        $this->character->setClass(SocialClass::ROGUE);
        $this->helper->assert_attacker_hits_with_roll($this->character, 9, new Character());
    }

    public function test_dexterity_modifier_of_target_is_ignored_if_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::DEX, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->helper->assert_attacker_hits_with_roll($this->character, 14, $target);
    }

    public function test_dexterity_modifier_of_target_is_not_ignored_if_not_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 6);
        $this->character->setAbility(Abilities::DEX, 2);
        $this->character->setClass(SocialClass::ROGUE);
        $this->helper->assert_attacker_hits_with_roll($this->character, 12, $target);
    }

    public function test_when_monk_attack_is_successful_other_character_takes_3_points_of_damage_when_hit()
    {
        $this->character->setClass(SocialClass::MONK);
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 15, $target);
        $this->helper->assert_has_remaining_hp(2, $target);
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_paladin_plus_2_to_attack_when_attacking_evil_characters()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, 10, $target);

        $target->setAlignment(Alignment::EVIL);
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
    }

    public function test_paladin_plus_2_to_damage_when_attacking_evil_characters()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 10, $target);
        $this->helper->assert_has_remaining_hp(4, $target);

        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->helper->createAttackRoll($this->character, 8, $target);
        $this->helper->assert_has_remaining_hp(2, $target);
    }

    public function test_paladins_critical_hit_is_dealt_and_the_damage_is_tripled_when_target_is_evil()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(3, $target);

        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(-4, $target);
        $this->assertTrue($target->isDead());
    }
}