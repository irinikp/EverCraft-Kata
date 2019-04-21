<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\Abilities;
use EverCraft\Character;
use EverCraft\Races\Race;

class AbilitiesTest extends \PHPUnit\Framework\TestCase
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
        $this->character->setAbility(Abilities::STR, 0);
        $this->assertNotEquals(0, $this->character->getAbilities()->getStrength());
        $this->character->setAbility(Abilities::STR, 1);
        $this->assertEquals(1, $this->character->getAbilities()->getStrength());
        $this->character->setAbility(Abilities::STR, 20);
        $this->assertEquals(20, $this->character->getAbilities()->getStrength());
        $this->character->setAbility(Abilities::STR, 21);
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

    public function test_add_dexterity_modifier_to_armor_class()
    {
        $this->character->setAbility(Abilities::DEX, 15);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_add_constitution_modifier_to_hit_points()
    {
        $this->character->setAbility(Abilities::CON, 15);
        $this->assertEquals(7, $this->character->getHp());
        $this->assertEquals(7, $this->character->getMaxHp());
    }

    public function test_add_constitution_modifier_to_hit_points_always_at_least_1_hp()
    {
        $this->character->setAbility(Abilities::CON, 1);
        $this->assertEquals(1, $this->character->getHp());
        $this->assertEquals(1, $this->character->getMaxHp());
    }

    public function test_orc_has_plus_2_to_strength_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ORC, Abilities::STR, +2);
    }

    public function test_orc_has_minus_1_to_intelligence_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ORC, Abilities::INT, -1);
    }

    public function test_orc_has_minus_1_to_wisdom_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ORC, Abilities::WIS, -1);
    }

    public function test_orc_has_minus_1_to_charisma_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ORC, Abilities::CHA, -1);
    }

    public function test_dwarf_has_plus_1_to_constitution_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::DWARF, Abilities::CON, 1);
    }

    public function test_dwarf_has_minus_1_to_charisma_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::DWARF, Abilities::CHA, -1);
    }

    public function test_dwarf_doubles_con_modifier_when_adding_to_hit_points_per_level_if_positive()
    {
        $this->character->setRace(Race::DWARF);
        $this->assertEquals(7, $this->character->getMaxHp());
        $this->character->setAbility(Abilities::CON, 17); // modifier 4
        $this->assertEquals(13, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(26, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(39, $this->character->getMaxHp());
    }

    public function test_dwarf_not_doubles_con_modifier_when_adding_to_hit_points_per_level_if_negative()
    {
        $this->character->setRace(Race::DWARF);
        $this->character->setAbility(Abilities::CON, 4); // modifier -2
        $this->assertEquals(3, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(6, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(9, $this->character->getMaxHp());
    }

    public function test_elf_has_plus_1_to_dexterity_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ELF, Abilities::DEX, 1);
    }

    public function test_elf_has_minus_1_to_constitution_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::ELF, Abilities::CON, -1);
    }

    public function test_halfling_has_plus_1_to_dexterity_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::HALFLING, Abilities::DEX, 1);
    }

    public function test_halfling_has_minus_1_to_strength_modifier()
    {
        $this->helper->create_test_for_race_ability_modifier($this->character, Race::HALFLING, Abilities::STR, -1);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll13()
    {
        $this->character->setAbility(Abilities::STR, 3);
        $hits = $this->helper->createAttackRoll($this->character, 13, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str3_roll14()
    {
        $this->character->setAbility(Abilities::STR, 3);
        $hits = $this->helper->createAttackRoll($this->character, 14, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll6()
    {
        $this->character->setAbility(Abilities::STR, 17);
        $hits = $this->helper->createAttackRoll($this->character, 6, null);
        $this->assertFalse($hits);
    }

    public function test_add_strength_modifier_to_attack_roll_scenario_str17_roll7()
    {
        $this->character->setAbility(Abilities::STR, 17);
        $hits = $this->helper->createAttackRoll($this->character, 7, null);
        $this->assertTrue($hits);
    }

    public function test_add_strength_modifier_to_damage_scenario_str3_roll14()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 3);
        $this->helper->createAttackRoll($this->character, 14, $target);
        $this->helper->assert_has_remaining_hp(4, $target);
    }

    public function test_add_strength_modifier_to_damage_scenario_str17_roll7()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 17);
        $this->helper->createAttackRoll($this->character, 7, $target);
        $this->helper->assert_has_remaining_hp(1, $target);
    }

    public function test_double_strength_modifier_to_critical_hits()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 12);
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(1, $target);
    }

    public function test_add_dexterity_modifier_to_armor_class_of_target()
    {
        $target = new Character();
        $target->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::STR, 2);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 16);
    }

}
