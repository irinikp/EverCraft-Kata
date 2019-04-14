<?php

namespace Tests;

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\CombatAction;
use EverCraft\Races\Race;

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

    public function test_default_race_is_human()
    {
        $this->assertEquals(Race::HUMAN, $this->character->getRaceName());
    }

    public function test_orc_is_a_valid_race()
    {
        $this->character->setRace(Race::ORC);
        $this->assertEquals(Race::ORC, $this->character->getRaceName());
    }

    public function test_orc_has_plus_2_to_strength_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ORC, Abilities::STR, +2);
    }

    public function test_orc_has_minus_1_to_intelligence_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ORC, Abilities::INT, -1);
    }

    public function test_orc_has_minus_1_to_wisdom_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ORC, Abilities::WIS, -1);
    }

    public function test_orc_has_minus_1_to_charisma_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ORC, Abilities::CHA, -1);
    }

    public function test_orc_has_plus_2_to_armor_class()
    {
        $this->character->setRace(Race::ORC);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_orc_monk_has_plus_2_to_armor_class()
    {
        $this->character->setRace(Race::ORC);
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::WIS, 16);
        $this->assertEquals(16, $this->character->getAc());
    }

    public function test_dwarf_has_plus_1_to_constitution_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::DWARF, Abilities::CON, 1);
    }

    public function test_dwarf_has_minus_1_to_charisma_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::DWARF, Abilities::CHA, -1);
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

    public function test_dwarf_plus_2_to_attack_when_attacking_orcs()
    {
        $this->character->setRace(Race::DWARF);
        $target = new Character();
        $this->assert_attacker_hits_with_roll(10, $target);

        $target->setRace(Race::ORC);
        $this->assert_attacker_hits_with_roll(10, $target);
    }

    public function test_dwarf_plus_2_to_damage_when_attacking_orcs()
    {
        $this->character->setRace(Race::DWARF);
        $target = new Character();
        $this->createAttackRoll(10, $target);
        $this->assertEquals(4, $target->getHp());

        $target = new Character();
        $target->setRace(Race::ORC);
        $this->createAttackRoll(10, $target);
        $this->assertEquals(2, $target->getHp());
    }

    public function test_elf_has_plus_1_to_dexterity_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ELF, Abilities::DEX, 1);
    }

    public function test_elf_has_minus_1_to_constitution_modifier()
    {
        $this->create_test_for_race_ability_modifier(Race::ELF, Abilities::CON, -1);
    }

    public function test_elf_does_critical_hit_on_19_and_20()
    {
        $this->character->setRace(Race::ELF);
        $this->assertFalse($this->character->getRace()->isCritical(18));
        $this->assertTrue($this->character->getRace()->isCritical(19));
        $this->assertTrue($this->character->getRace()->isCritical(20));
    }

    public function test_elf_plus_two_to_ac_when_being_attacked_by_orcs()
    {
        $target = new Character();
        $this->character->setRace(Race::ORC);
        $this->assert_attacker_hits_with_roll(8, $target);

        $target->setRace(Race::ELF);
        $this->assert_attacker_hits_with_roll(11, $target);
    }

    public function test_3rd_level_paladin_dwarf_attacks_5th_level_evil_orc_monk()
    {
        // Set up Characters
        $this->character->setRace(Race::DWARF);
        $this->character->setClass(SocialClass::PALADIN);
        $this->assertEquals(10, $this->character->getHp()); // Paladin 8 HP/level, dwarf +2 Hp/level

        $target = new Character();
        $target->setRace(Race::ORC);
        $target->setClass(SocialClass::MONK);
        $target->setAlignment(Alignment::EVIL);

        // Monk 6 HP/level
        $this->assert_has_remaining_hp(6, $target);


        // Battle round 1
        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, Orc +2 on AC
        $this->assert_attacker_hits_with_roll(8, $target);
        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->assert_has_remaining_hp(1, $target);


        // +2 to attack modifier from STR because of ORC
        $this->assert_defender_hits_with_roll(8, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->assert_has_remaining_hp(5, $this->character);

        $this->assertEquals(10, $this->character->getXp());
        $this->assertEquals(10, $target->getXp());

        // Level up
        $this->character->addXp(990);
        $this->assertEquals(2, $this->character->getLevel());
        $target->addXp(990);
        $this->assertEquals(2, $target->getLevel());

        $this->assert_has_remaining_hp(15, $this->character);
        $this->assert_has_remaining_hp(7, $target);

        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, +1 attack logo paladin level, Orc +2 on AC
        $this->assert_attacker_hits_with_roll(7, $target);
        // +2 to attack modifier from STR because of ORC + 1 apo monk level up
        $this->assert_defender_hits_with_roll(7, $target);
        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->assert_has_remaining_hp(2, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->assert_has_remaining_hp(10, $this->character);

        $this->assertEquals(1010, $this->character->getXp());
        $this->assertEquals(1010, $target->getXp());

        // Level up
        $this->character->addXp(990);
        $this->assertEquals(3, $this->character->getLevel());
        $target->addXp(990);
        $this->assertEquals(3, $target->getLevel());

        $this->assert_has_remaining_hp(20, $this->character);
        $this->assert_has_remaining_hp(8, $target);

        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, +2 attack logo paladin level, Orc +2 on AC
        $this->assert_attacker_hits_with_roll(6, $target);
        // +2 to attack modifier from STR because of ORC + 2 apo monk level up
        $this->assert_defender_hits_with_roll(6, $target);

        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->assert_has_remaining_hp(3, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->assert_has_remaining_hp(15, $this->character);
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
        $hits = $this->createAttackRoll($dice, $target); // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, Orc +2 on AC
        $this->assertTrue($hits);
    }

    /**
     * @param string $race
     * @param string $ability
     * @param int    $ability_change
     */
    private function create_test_for_race_ability_modifier($race, $ability, $ability_change): void
    {
        $human_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->character->setRace($race);
        $race_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->assertEquals(($human_ability_modifier + $ability_change), $race_ability_modifier);
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