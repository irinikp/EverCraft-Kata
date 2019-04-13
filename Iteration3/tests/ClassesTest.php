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
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_fighter_attack_roll_increases_by_1_in_every_level()
    {
        $this->character->setClass(SocialClass::FIGHTER);
        // Level 2
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
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
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll(1));
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll(2));
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll(3));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(4));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(5));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(6));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(7));
        $this->assertEquals(5, $this->character->getClass()->getAttackRoll(8));
        $this->assertEquals(6, $this->character->getClass()->getAttackRoll(9));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(10));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(11));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(12));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(13));
        $this->assertEquals(9, $this->character->getClass()->getAttackRoll(14));
        $this->assertEquals(10, $this->character->getClass()->getAttackRoll(15));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(16));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(17));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(18));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(19));
        $this->assertEquals(13, $this->character->getClass()->getAttackRoll(20));
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
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll(1));
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll(2));
        $this->assertEquals(6, $this->character->getClass()->getAttackRoll(7));
        $this->assertEquals(19, $this->character->getClass()->getAttackRoll(20));
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
}