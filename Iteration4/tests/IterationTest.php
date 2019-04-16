<?php

namespace Tests;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\CombatAction;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\Waraxe;
use EverCraft\Items\Weapons\Weapon;

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
        $this->character->wield(new Longsword());
        $this->character->wield(new Waraxe());
        $this->assertEquals('Waraxe', $this->character->getWieldingWeaponName());
    }

    public function test_longsword_does_5_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::LONGSWORD, 5, 0);
    }

    public function test_waraxe_does_6_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::WARAXE, 6, 0);
    }

    public function test_waraxe_plus_2_does_8_points_of_damage()
    {
        $this->assert_damage_of_weapon(Weapon::WARAXE, 8, 2);
    }

    public function test_waraxe_plus_2_has_plus_2_attack_bonus()
    {
        $this->character->wield(new Waraxe(2));
        $target = new Character();
        $this->assert_attacker_hits_with_roll(8, $target);
    }

    public function test_plus_2_weapon_triples_damage_on_critical()
    {
        $this->character->wield(new Waraxe(2));
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(-19, $target);
    }

    public function test_rogues__with_plus_2_weapon_does_quadruple_critical_hit()
    {
        $this->character->setClass(SocialClass::ROGUE);
        $this->character->wield(new Waraxe(2));
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);
        $this->assert_has_remaining_hp(-27, $target);
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

    private function assert_damage_of_weapon($weapon, $damage, $magical)
    {
        $weapon_class = '\\EverCraft\\Items\\Weapons\\' . $weapon;
        $this->character->wield(new $weapon_class($magical));
        $target = new Character();
        $this->createAttackRoll(10, $target);
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