<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use Dnd\Alignment;
use Dnd\Battle;
use Dnd\Character;
use Dnd\CombatAction;

class TestIteration1 extends \PHPUnit\Framework\TestCase
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

    public function test_roll_dice()
    {
        for ($i = 1; $i <= 20; $i++) {
            $dice = $this->character->roll($i);
            $this->assertGreaterThanOrEqual(1, $dice);
            $this->assertLessThanOrEqual($i, $dice);
        }
    }

    public function test_attack_roll_1()
    {
        $hits = $this->create_mock_attack_roll(1);
        $this->assertEquals(false, $hits);
    }

    public function test_attack_roll_5()
    {
        $hits = $this->create_mock_attack_roll(5);
        $this->assertEquals(false, $hits);
    }

    public function test_attack_roll_10()
    {
        $hits = $this->create_mock_attack_roll(10);
        $this->assertEquals(true, $hits);
    }

    public function test_attack_roll_15()
    {
        $hits = $this->create_mock_attack_roll(15);
        $this->assertEquals(true, $hits);
    }

    public function test_attack_roll_20()
    {
        $hits = $this->create_mock_attack_roll(20);
        $this->assertEquals(true, $hits);
    }

    private function create_mock_attack_roll($dice)
    {
        $character = \Mockery::mock(Character::class);

        $character->shouldReceive('roll')->withArgs([20])->once()->andReturn($dice);
        $action = new CombatAction($character, new Character());
        return $action->attackRoll();
    }
}
