<?php

namespace Tests;

use EverCraft\Character;
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

}