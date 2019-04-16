<?php

namespace Tests;

use EverCraft\Character;
use EverCraft\CombatAction;
use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\Waraxe;

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
        $this->character->wield(new Longsword());
        $target = new Character();
        $this->createAttackRoll(10, $target);
        $this->assert_has_remaining_hp(0, $target);
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