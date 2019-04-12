<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Alignment;
use Dnd\Character;
use Dnd\InvalidAlignmentException;

class CreateCharacterTest extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
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
        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment('innocent');
    }

    public function test_ac()
    {
        $this->assertEquals(10, $this->character->getAc());
    }

    public function test_hp()
    {
        $this->assertEquals(5, $this->character->getHp());
        $this->assertEquals(5, $this->character->getMaxHp());
    }

    public function test_roll_dice()
    {
        for ($i = 1; $i <= 20; $i++) {
            $dice = $this->character->roll($i);
            $this->assertGreaterThanOrEqual(1, $dice);
            $this->assertLessThanOrEqual($i, $dice);
        }
    }

    public function test_new_character_is_alive()
    {
        $this->assertFalse($this->character->isDead());
    }

    public function test_a_new_character_has_zero_xp()
    {
        $this->assertEquals(0, $this->character->getXp());
    }

    public function test_default_level()
    {
        $this->assertEquals(1, $this->character->getLevel());
    }

    public function test_attack_roll_is_initially_zero()
    {
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
    }
}
