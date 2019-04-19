<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\Abilities;
use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\Races\Race;

class CombatActionTest extends \PHPUnit\Framework\TestCase
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

    public function test_attack_roll_1()
    {
        $hits = $this->helper->createAttackRoll($this->character, 1);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_5()
    {
        $hits = $this->helper->createAttackRoll($this->character, 5);
        $this->assertFalse($hits);
    }

    public function test_attack_roll_10()
    {
        $hits = $this->helper->createAttackRoll($this->character, 10);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_15()
    {
        $hits = $this->helper->createAttackRoll($this->character, 15);
        $this->assertTrue($hits);
    }

    public function test_attack_roll_20()
    {
        $hits = $this->helper->createAttackRoll($this->character, 20);
        $this->assertTrue($hits);
    }

    public function test_when_attack_is_successful_other_character_takes_1_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 15, $target);

        $this->helper->assert_has_remaining_hp(4, $target);
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_when_two_attacks_are_successful_other_character_takes_2_point_of_damage_when_hit()
    {
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 15, $target);
        $this->helper->createAttackRoll($this->character, 15, $target);
        $this->helper->assert_has_remaining_hp(3, $target);
    }

    public function test_when_attack_is_unsuccessful_other_character_does_not_take_damage_when_hit()
    {
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 9, $target);
        $this->helper->assert_has_remaining_hp(5, $target);
    }

    public function test_if_a_roll_is_a_20_then_a_critical_hit_is_dealt_and_the_damage_is_doubled()
    {
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(3, $target);
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

    public function test_minimum_damage_is_always_1_even_on_a_critical_hit()
    {
        $target = new Character();
        $this->character->setAbility(Abilities::STR, 2);
        $this->helper->createAttackRoll($this->character, 20, $target);
        $this->helper->assert_has_remaining_hp(4, $target);
    }

    public function test_when_a_successful_attack_occurs_the_character_gains_10_experience_points()
    {
        $this->helper->createAttackRoll($this->character, 10);
        $this->assertEquals(10, $this->character->getXp());
        $this->assertTrue(true);
    }

    public function test_compute_attack_roll_when_attacking()
    {
        $this->character->addXp(3000);
        $hits = $this->helper->createAttackRoll($this->character, 8, null);
        $this->assertTrue($hits);

    }

    public function test_3rd_level_paladin_dwarf_attacks_5th_level_evil_orc_monk()
    {
        // Set up Characters
        $this->character->setRace(Race::DWARF);
        $this->character->setClass(SocialClass::PALADIN);
        // Paladin 8 HP/level, dwarf +2 Hp/level
        $this->helper->assert_has_remaining_hp(10, $this->character);

        $target = new Character();
        $target->setRace(Race::ORC);
        $target->setClass(SocialClass::MONK);
        $target->setAlignment(Alignment::EVIL);

        // Monk 6 HP/level
        $this->helper->assert_has_remaining_hp(6, $target);


        // Battle round 1
        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, Orc +2 on AC
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->helper->assert_has_remaining_hp(1, $target);


        // +2 to attack modifier from STR because of ORC
        $this->helper->assert_defender_hits_with_roll($this->character, 8, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->helper->assert_has_remaining_hp(5, $this->character);

        $this->assertEquals(10, $this->character->getXp());
        $this->assertEquals(10, $target->getXp());

        // Level up
        $this->character->addXp(990);
        $this->assertEquals(2, $this->character->getLevel());
        $target->addXp(990);
        $this->assertEquals(2, $target->getLevel());

        $this->helper->assert_has_remaining_hp(15, $this->character);
        $this->helper->assert_has_remaining_hp(7, $target);

        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, +1 attack logo paladin level, Orc +2 on AC
        $this->helper->assert_attacker_hits_with_roll($this->character, 7, $target);
        // +2 to attack modifier from STR because of ORC + 1 apo monk level up
        $this->helper->assert_defender_hits_with_roll($this->character, 7, $target);
        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->helper->assert_has_remaining_hp(2, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->helper->assert_has_remaining_hp(10, $this->character);

        $this->assertEquals(1010, $this->character->getXp());
        $this->assertEquals(1010, $target->getXp());

        // Level up
        $this->character->addXp(990);
        $this->assertEquals(3, $this->character->getLevel());
        $target->addXp(990);
        $this->assertEquals(3, $target->getLevel());

        $this->helper->assert_has_remaining_hp(20, $this->character);
        $this->helper->assert_has_remaining_hp(8, $target);

        // Dwarf +2 to attack Orc, Paladin +2 to attack Evil, +2 attack logo paladin level, Orc +2 on AC
        $this->helper->assert_attacker_hits_with_roll($this->character, 6, $target);
        // +2 to attack modifier from STR because of ORC + 2 apo monk level up
        $this->helper->assert_defender_hits_with_roll($this->character, 6, $target);

        // 1 + Dwarf +2 damage VS Orc, Paladin +2 damage VS Evil
        $this->helper->assert_has_remaining_hp(3, $target);
        // Monk 3 damage + 2 from Orc's STR modifier
        $this->helper->assert_has_remaining_hp(15, $this->character);
    }
}
