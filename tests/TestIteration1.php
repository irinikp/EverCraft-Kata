<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Alignment;
use Dnd\Character;

class TestIteration1 extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp() : void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_get_set_name()
    {
        $this->assertEquals('Bilbur', $this->character->getName());
    }

    public function test_get_set_alignment()
    {
        $this->character->setAlignment(Alignment::GOOD);
        $this->assertEquals(Alignment::GOOD, $this->character->getAlignment());
    }

    public function test_alignments_default_values()
    {
        $this->character->setAlignment('innocent');
        $this->assertNotEquals('innocent', $this->character->getAlignment());
    }

    public function test_ac()
    {
        $this->assertEquals(10, $this->character->getAc());
    }

    public function test_hp()
    {
        $this->assertEquals(5, $this->character->getHp());
    }
}
