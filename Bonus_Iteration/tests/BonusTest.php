<?php

namespace Tests;

use EverCraft\Items\Weapons\Longsword;
use EverCraft\Items\Weapons\NunChucks;
use EverCraft\Items\Weapons\Waraxe;

require __DIR__ . '/../vendor/autoload.php';

class BonusTest extends \PHPUnit\Framework\TestCase
{
    public function test_non_missile_weapon_range_is_1()
    {
        $this->assertEquals(1, (new Longsword())->getMinRange());
        $this->assertEquals(1, (new Longsword())->getMaxRange());
        $this->assertEquals(1, (new NunChucks())->getMinRange());
        $this->assertEquals(1, (new NunChucks())->getMaxRange());
        $this->assertEquals(1, (new Waraxe())->getMinRange());
        $this->assertEquals(1, (new Waraxe())->getMaxRange());
    }
}