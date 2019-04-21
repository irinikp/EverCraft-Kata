<?php

namespace Tests;

use EverCraft\Abilities;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\InvalidAlignmentException;
use EverCraft\Races\Race;

require __DIR__ . '/../vendor/autoload.php';

class RacesTest extends \PHPUnit\Framework\TestCase
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

    public function test_default_race_is_human()
    {
        $this->assertEquals(Race::HUMAN, $this->character->getRaceName());
    }

    public function test_orc_is_a_valid_race()
    {
        $this->character->setRace(Race::ORC);
        $this->assertEquals(Race::ORC, $this->character->getRaceName());
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

    public function test_elf_does_critical_hit_on_19_and_20()
    {
        $this->character->setRace(Race::ELF);
        $this->assertFalse($this->character->getRace()->isCritical(18));
        $this->assertTrue($this->character->getRace()->isCritical(19));
        $this->assertTrue($this->character->getRace()->isCritical(20));
    }

    public function test_halfling_cannot_be_evil()
    {
        $this->character->setRace(Race::HALFLING);
        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment('Evil');
    }

    public function test_dwarf_plus_2_to_damage_when_attacking_orcs()
    {
        $this->character->setRace(Race::DWARF);
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 10, $target);
        $this->assertEquals(4, $target->getHp());

        $target = new Character();
        $target->setRace(Race::ORC);
        $this->helper->createAttackRoll($this->character, 10, $target);
        $this->assertEquals(2, $target->getHp());
    }

    public function test_dwarf_plus_2_to_attack_when_attacking_orcs()
    {
        $this->character->setRace(Race::DWARF);
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 10);

        $target->setRace(Race::ORC);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 10);
    }

    public function test_elf_plus_two_to_ac_when_being_attacked_by_orcs()
    {
        $target = new Character();
        $this->character->setRace(Race::ORC);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 8);

        $target->setRace(Race::ELF);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 11);
    }

    public function test_halfling_plus_two_to_ac_when_being_attacked_by_orcs()
    {
        $target = new Character();
        $this->character->setRace(Race::ORC);
        // Orc hits with +2 from STR
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 8);

        // Halfling has +1 to AC from DEX plus 2 from this test
        $target->setRace(Race::HALFLING);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 11);
    }

    public function test_halfling_plus_two_to_ac_when_being_attacked_by_dwarves()
    {
        $target = new Character();
        $this->character->setRace(Race::DWARF);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 10);

        // Halfling has +1 to AC from DEX plus 2 from this test
        $target->setRace(Race::HALFLING);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 13);
    }

    public function test_halfling_plus_two_to_ac_when_being_attacked_by_elf()
    {
        $target = new Character();
        $this->character->setRace(Race::ELF);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 10);

        // Halfling has +1 to AC from DEX plus 2 from this test
        $target->setRace(Race::HALFLING);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 13);
    }

    public function test_halfling_plus_two_to_ac_when_being_attacked_by_human()
    {
        $target = new Character();
        $this->character->setRace(Race::HUMAN);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 10);

        // Halfling has +1 to AC from DEX plus 2 from this test
        $target->setRace(Race::HALFLING);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 13);
    }

    public function test_halfling_non_plus_two_to_ac_when_being_attacked_by_halfling()
    {
        $target = new Character();
        $this->character->setRace(Race::HALFLING);
        // Has -1 to attack from STR
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 11);

        // Halfling has +1 to AC from DEX
        $target->setRace(Race::HALFLING);
        $this->helper->assert_attacker_hits_with_roll($this->character, $target, 12);
    }
}