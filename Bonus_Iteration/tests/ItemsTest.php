<?php

namespace Tests;

use EverCraft\Alignment;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\Items\AmuletOfTheHeavens;
use EverCraft\Items\Armors;
use EverCraft\Items\Armors\Leather;
use EverCraft\Items\Armors\LeatherOfDamageReduction;
use EverCraft\Items\Armors\Plate;
use EverCraft\Items\BeltOfGiantStrength;
use EverCraft\Items\RingOfProtection;
use EverCraft\Items\Shields\Shield;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\NunChucks;
use EverCraft\Items\Weapons\Waraxe;
use EverCraft\Items\Weapons\Weapon;
use EverCraft\Races\Race;

require __DIR__ . '/../vendor/autoload.php';

class ItemsTest extends \PHPUnit\Framework\TestCase
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

    public function test_character_can_wield_only_1_weapon()
    {
        $this->character->use(new Longsword());
        $this->assertTrue(true);
        $this->character->use(new Waraxe());
        $this->assertEquals('Waraxe', $this->character->getObjectName('weapon'));
    }

    public function test_longsword_does_5_points_of_damage()
    {
        $this->helper->assert_damage_of_weapon($this->character, Weapon::LONGSWORD, 5, 0, $this->character);
    }

    public function test_waraxe_does_6_points_of_damage()
    {
        $this->helper->assert_damage_of_weapon($this->character, Weapon::WARAXE, 6, 0, $this->character);
    }

    public function test_waraxe_plus_2_does_8_points_of_damage()
    {
        $this->helper->assert_damage_of_weapon($this->character, Weapon::WARAXE, 8, 2, $this->character);
    }

    public function test_waraxe_plus_2_has_plus_2_attack_bonus()
    {
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
    }

    public function test_plus_2_weapon_triples_damage_on_critical()
    {
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 20, $target, 0);
        $this->helper->assert_has_remaining_hp(-19, $target);
    }

    public function test_rogues_with_plus_2_weapon_does_quadruple_critical_hit()
    {
        $this->character->setClass(SocialClass::ROGUE);
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->helper->createAttackRoll($this->character, 20, $target, 0);
        $this->helper->assert_has_remaining_hp(-27, $target);
    }

    public function test_elven_longsword_does_6_points_of_damage()
    {
        $this->helper->assert_damage_of_weapon($this->character, Weapon::ELVEN_LONGSWORD, 6, 0, $this->character);
    }

    public function test_elven_longsword_has_plus_1_to_attack()
    {
        $this->character->use(new Longsword\Elven());
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, 9, $target);
    }

    public function test_elven_longsword_has_plus_2_to_attack_when_wielded_by_elf()
    {
        $this->character->setRace(Race::ELF);
        $this->character->use(new Longsword\Elven());
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
    }

    public function test_elven_longsword_has_plus_2_to_attack_when_wielded_against_orc()
    {
        $target = new Character();
        $target->setRace(Race::ORC);
        $this->helper->assert_attacker_hits_with_roll($this->character, 12, $target);
        $this->character->use(new Longsword\Elven());
        $this->helper->assert_attacker_hits_with_roll($this->character, 10, $target);
    }

    public function test_elven_longsword_has_plus_5_to_attack_when_wielded_by_elf_against_orc()
    {
        $target = new Character();
        $target->setRace(Race::ORC);
        $this->character->setRace(Race::ELF);
        $this->helper->assert_attacker_hits_with_roll($this->character, 12, $target);
        $this->character->use(new Longsword\Elven());
        $this->helper->assert_attacker_hits_with_roll($this->character, 7, $target);
    }

    public function test_monk_does_6_damage_with_nunchunks()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->helper->assert_damage_of_weapon($this->character, Weapon::NUNCHUCKS, 6, 0, $this->character);
    }

    public function test_non_monk_has_minus_4_penalty_to_attack_with_nunchunks()
    {
        $this->character->use(new NunChucks());
        $target = new Character();
        $this->helper->assert_attacker_hits_with_roll($this->character, 14, $target);
        $this->helper->assert_damage_of_weapon($this->character, Weapon::NUNCHUCKS, 6, 0, $this->character, 14);
    }

    public function test_character_can_don_one_shield()
    {
        // In order to confirm that a character holds only one shield, I compare the AC before and after he picks up the 2nd shield
        // If he could hold 2 shields, his AC would change after picking up the 2nd one.
        $this->character->use(new Shield());
        $first_shield_ac = $this->character->getAc();
        $this->character->use(new Shield());
        $this->assertEquals($first_shield_ac, $this->character->getAc());
    }

    public function test_character_can_wear_one_armor()
    {
        $this->character->setClass('Fighter');
        $this->character->use(new Plate());
        $this->character->use(new Leather());
        $this->assertEquals('Leather', $this->character->getObjectName('armor'));
    }

    public function test_leather_armor_adds_2_to_ac()
    {
        $old_ac = $this->character->getAc();
        $this->character->use(new Leather());
        $this->assertEquals(($old_ac + 2), $this->character->getAc());
    }

    public function test_plate_armor_can_be_wore_by_fighters()
    {
        $this->character->setClass('Fighter');
        $this->character->use(new Plate());
        $this->assertEquals('Plate', $this->character->getObjectName('armor'));
    }

    public function test_plate_armor_can_be_wore_by_dwarves()
    {
        $this->character->setRace('Dwarf');
        $this->character->use(new Plate());
        $this->assertEquals('Plate', $this->character->getObjectName('armor'));
    }

    public function test_plate_armor_cannot_be_wore_by_non_fighters_and_non_dwarves()
    {
        $this->character->use(new Plate());
        $this->assertNotEquals('Plate', $this->character->getObjectName('armor'));
    }

    public function test_plate_armor_adds_8_to_ac()
    {
        $this->character->setRace('Dwarf');
        $old_ac = $this->character->getAc();
        $this->character->use(new Plate());
        $this->assertEquals(($old_ac + 8), $this->character->getAc());
    }

    public function test_leather_armor_of_damage_reduction_adds_2_to_ac()
    {
        $old_ac = $this->character->getAc();
        $this->character->use(new LeatherOfDamageReduction());
        $this->assertEquals(($old_ac + 2), $this->character->getAc());
    }

    public function test_leather_armor_of_damage_reduction_reduces_all_damage_received_by_2()
    {
        $target = new Character();
        $target->use(new LeatherOfDamageReduction());
        $this->character->use(new Waraxe());
        $this->helper->createAttackRoll($this->character, 13, $target, 0);
        $this->helper->assert_has_remaining_hp(1, $target);
    }

    public function test_reducing_receiving_damage_does_not_give_extra_hit_points()
    {
        $target = new Character();
        $target->use(new LeatherOfDamageReduction());
        $this->helper->createAttackRoll($this->character, 13, $target, 0);
        $this->helper->assert_has_remaining_hp(5, $target);
    }

    public function test_elven_chain_mail_adds_5_to_ac()
    {
        $old_ac = $this->character->getAc();
        $this->character->use(new Armors\ChainMail\Elven());
        $this->assertEquals(($old_ac + 5), $this->character->getAc());
    }

    public function test_elven_chain_mail_adds_8_to_ac_if_worn_by_an_elf()
    {
        $this->character->setRace(Race::ELF);
        $old_ac = $this->character->getAc();
        $this->character->use(new Armors\ChainMail\Elven());
        $this->assertEquals(($old_ac + 8), $this->character->getAc());
    }

    public function test_elven_chainmail_gives_plus_1_to_attack_when_worn_by_elf()
    {
        $this->character->setRace(Race::ELF);
        $target = new Character();
        $this->character->use(new Armors\ChainMail\Elven());
        $this->helper->assert_attacker_hits_with_roll($this->character, 9, $target);
    }

    public function test_shield_adds_3_to_ac()
    {
        $old_ac = $this->character->getAc();
        $this->character->use(new Shield());
        $this->assertEquals(($old_ac + 3), $this->character->getAc());
    }

    public function test_shield_reduces_attack_roll_by_four()
    {
        $target = new Character();
        $this->character->use(new Shield());
        $this->helper->assert_attacker_hits_with_roll($this->character, 14, $target);
    }

    public function test_shield_reduces_attack_roll_by_two_when_used_by_a_fighter()
    {
        $target = new Character();
        $this->character->setClass(SocialClass::FIGHTER);
        $this->character->use(new Shield());
        $this->helper->assert_attacker_hits_with_roll($this->character, 12, $target);
    }

    public function test_ring_of_protection_adds_2_to_ac()
    {
        $old_ac = $this->character->getAc();
        $this->character->use(new RingOfProtection());
        $this->assertEquals(($old_ac + 2), $this->character->getAc());
    }

    public function test_belt_of_giant_strength()
    {
        $old_str = $this->character->getAbilities()->getStrength();
        $belt    = new BeltOfGiantStrength();
        $this->character->use($belt);
        $this->assertEquals(($old_str + 4), $this->character->getAbilities()->getStrength());
        $this->character->stopUsing($belt);
        $this->assertEquals($old_str, $this->character->getAbilities()->getStrength());
    }

    public function test_amulet_of_heavens_gives_plus_1_to_attack_against_neutral()
    {
        $this->character->use(new AmuletOfTheHeavens());
        $target = new Character();
        $target->setAlignment(Alignment::NEUTRAL);
        $this->helper->assert_attacker_hits_with_roll($this->character, 9, $target);
    }

    public function test_amulet_of_heavens_gives_plus_2_to_attack_against_evil()
    {
        $this->character->use(new AmuletOfTheHeavens());
        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
    }

    public function test_amulet_of_heavens_worn_by_paladin_gives_plus_2_to_attack_against_neutral()
    {
        $this->character->setClass(SocialClass::PALADIN);
        $this->character->use(new AmuletOfTheHeavens());
        $target = new Character();
        $target->setAlignment(Alignment::NEUTRAL);
        $this->helper->assert_attacker_hits_with_roll($this->character, 8, $target);
    }

    public function test_amulet_of_heavens_worn_by_paladin_gives_plus_4_to_attack_against_evil()
    {
        // Paladins already have +2 to attack against Evil, therefore when wearing the amulet they have +6 to attack roll
        $this->character->setClass(SocialClass::PALADIN);
        $this->character->use(new AmuletOfTheHeavens());
        $target = new Character();
        $target->setAlignment(Alignment::EVIL);
        $this->helper->assert_attacker_hits_with_roll($this->character, 4, $target);
    }

}