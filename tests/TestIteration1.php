<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd;

class TestAlpha extends \PHPUnit\Framework\TestCase
{

    public function test_get_name()
    {
        $character = new Dnd\Character();
        $character->setName('Bilbur');
        $this->assertEquals('Bilbur', $character->getName());
    }
}
