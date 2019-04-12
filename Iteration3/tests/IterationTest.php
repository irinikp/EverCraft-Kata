<?php

namespace Tests;

use Dnd\Character;

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

    public function test_default_race_is_human()
    {
        $this->assertEquals('Human', $this->character->getRaceName());
    }

    public function test_orc_is_a_valid_race()
    {
        $this->character->setRace('Orc');
        $this->assertEquals('Orc', $this->character->getRaceName());
    }

    public function test_orc_has_plus_2_to_strength_modifier()
    {
        $this->create_test_for_race_ability_modifier('Orc', 'Strength', +2);
    }

    public function test_orc_has_minus_1_to_intelligence_modifier()
    {
        $this->create_test_for_race_ability_modifier('Orc', 'Intelligence', -1);
    }

    public function test_orc_has_minus_1_to_wisdom_modifier()
    {
        $this->create_test_for_race_ability_modifier('Orc', 'Wisdom', -1);
    }

    public function test_orc_has_minus_1_to_charisma_modifier()
    {
        $this->create_test_for_race_ability_modifier('Orc', 'Charisma', -1);
    }

    protected function create_test_for_race_ability_modifier($race, $ability, $ability_change)
    {
        $human_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->character->setRace($race);
        $race_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->assertEquals(($human_ability_modifier + $ability_change), $race_ability_modifier);
    }
}