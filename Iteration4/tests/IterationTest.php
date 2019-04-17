<?php

namespace Tests;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\CombatAction;
use EverCraft\Items\Armors\Leather;
use EverCraft\Items\Armors\Plate;
use EverCraft\Items\Shields\Shield;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\NunChucks;
use EverCraft\Items\Weapons\Waraxe;
use EverCraft\Items\Weapons\Weapon;
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

    public function test_character_can_wield_only_1_weapon()
    {
        $this->character->use(new Longsword());
        $this->assertTrue(true);
        $this->character->use(new Waraxe());
        $this->assertEquals('Waraxe', $this->character->getWieldingWeaponName());
    }

    public function test_longsword_does_5_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::LONGSWORD, 5, 0, $this->character);
    }

    public function test_waraxe_does_6_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::WARAXE, 6, 0, $this->character);
    }

    public function test_waraxe_plus_2_does_8_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::WARAXE, 8, 2, $this->character);
    }

    public function test_waraxe_plus_2_has_plus_2_attack_bonus()
    {
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->assert_attacker_hits_with_roll(8, $target);
    }

    public function test_plus_2_weapon_triples_damage_on_critical()
    {
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(-19, $target);
    }

    public function test_rogues__with_plus_2_weapon_does_quadruple_critical_hit()
    {
        $this->character->setClass(SocialClass::ROGUE);
        $this->character->use(new Waraxe(2));
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(-27, $target);
    }

    public function test_elven_longsword_does_6_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::ELVEN_LONGSWORD, 6, 0, $this->character);
    }

    public function test_elven_longsword_has_plus_1_to_attack()
    {
        $this->character->use(new Longsword\Elven());
        $target = new Character();
        $this->assert_attacker_hits_with_roll(9, $target);
    }

    public function test_elven_longsword_has_plus_2_to_attack_when_wielded_by_elf()
    {
        $this->character->setRace(Race::ELF);
        $this->character->use(new Longsword\Elven());
        $target = new Character();
        $this->assert_attacker_hits_with_roll(8, $target);
    }

    public function test_elven_longsword_has_plus_2_to_attack_when_wielded_against_orc()
    {
        $target = new Character();
        $target->setRace(Race::ORC);
        $this->assert_attacker_hits_with_roll(12, $target);
        $this->character->use(new Longsword\Elven());
        $this->assert_attacker_hits_with_roll(10, $target);
    }

    public function test_elven_longsword_has_plus_5_to_attack_when_wielded_by_elf_against_orc()
    {
        $target = new Character();
        $target->setRace(Race::ORC);
        $this->character->setRace(Race::ELF);
        $this->assert_attacker_hits_with_roll(12, $target);
        $this->character->use(new Longsword\Elven());
        $this->assert_attacker_hits_with_roll(7, $target);
    }

    public function test_monk_does_6_damage_with_nunchunks()
    {
        $this->character->setClass(SocialClass::MONK);
        $this->assert_damage_of_weapon(Weapon::NUNCHUCKS, 6, 0, $this->character);
    }

    public function test_non_monk_has_minus_4_penalty_to_attack_with_nunchunks()
    {
        $this->character->use(new NunChucks());
        $target = new Character();
        $this->assert_attacker_hits_with_roll(14, $target);
        $this->assert_damage_of_weapon(Weapon::NUNCHUCKS, 6, 0, $this->character, 14);
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
        $this->character->use(new Plate());
        $this->character->use(new Leather());
        $this->assertEquals('Leather', $this->character->getArmorName());
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
     * @param string    $weapon
     * @param int       $damage
     * @param int       $magical
     * @param Character $attacker
     * @param int       $dice
     */
    private function assert_damage_of_weapon($weapon, $damage, $magical, $attacker, $dice = 10)
    {
        $weapon_class = '\\EverCraft\\Items\\Weapons\\' . $weapon;
        $this->character->use(new $weapon_class($magical));
        $target = new Character();
        $this->createAttackRoll($dice, $target);
        $this->assert_has_remaining_hp(5 - $damage, $target);
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
     * @param int       $hp
     * @param Character $character
     */
    private function assert_has_remaining_hp($hp, $character): void
    {
        $this->assertEquals($hp, $character->getHp());
    }

}