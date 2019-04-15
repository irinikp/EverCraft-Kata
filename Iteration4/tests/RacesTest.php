<?php

namespace Tests;

use EverCraft\Abilities;
use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\InvalidAlignmentException;
use EverCraft\Races\Race;

require __DIR__ . '/../vendor/autoload.php';

class RacesTest extends \PHPUnit\Framework\TestCase
{
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }

    public function test_default_race_is_human()
    {
        $this->assertEquals(Race::HUMAN, $this->character->getRaceName());
    }

    public function test_orc_is_a_valid_race()
    {
        $this->character->setRace(Race::ORC);
        $this->assertEquals(Race::ORC, $this->character->getRaceName());
    }

    public function test_orc_has_plus_2_to_armor_class()
    {
        $this->character->setRace(Race::ORC);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_orc_monk_has_plus_2_to_armor_class()
    {
        $this->character->setRace(Race::ORC);
        $this->character->setClass(SocialClass::MONK);
        $this->character->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::WIS, 16);
        $this->assertEquals(16, $this->character->getAc());
    }

    public function test_elf_does_critical_hit_on_19_and_20()
    {
        $this->character->setRace(Race::ELF);
        $this->assertFalse($this->character->getRace()->isCritical(18));
        $this->assertTrue($this->character->getRace()->isCritical(19));
        $this->assertTrue($this->character->getRace()->isCritical(20));
    }

    public function test_halfling_cannot_be_evil()
    {
        $this->character->setRace(Race::HALFLING);
        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment('Evil');
    }
}