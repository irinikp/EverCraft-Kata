<?php

namespace Tests;

use Dnd\Abilities;
use Dnd\Character;
use Dnd\Classes\AbstractClass;
use Dnd\CombatAction;
use Dnd\Races\AbstractRace;

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
        $this->assertEquals(AbstractRace::HUMAN, $this->character->getRaceName());
    }

    public function test_orc_is_a_valid_race()
    {
        $this->character->setRace(AbstractRace::ORC);
        $this->assertEquals(AbstractRace::ORC, $this->character->getRaceName());
    }

    public function test_orc_has_plus_2_to_strength_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::ORC, Abilities::STR, +2);
    }

    public function test_orc_has_minus_1_to_intelligence_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::ORC, Abilities::INT, -1);
    }

    public function test_orc_has_minus_1_to_wisdom_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::ORC, Abilities::WIS, -1);
    }

    public function test_orc_has_minus_1_to_charisma_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::ORC, Abilities::CHA, -1);
    }

    public function test_orc_has_plus_2_to_armor_class()
    {
        $this->character->setRace(AbstractRace::ORC);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_orc_monk_has_plus_2_to_armor_class()
    {
        $this->character->setRace(AbstractRace::ORC);
        $this->character->setClass(AbstractClass::MONK);
        $this->character->setAbility(Abilities::DEX, 15);
        $this->character->setAbility(Abilities::WIS, 16);
        $this->assertEquals(16, $this->character->getAc());
    }

    public function test_dwarf_has_plus_1_to_constitution_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::DWARF, Abilities::CON, 1);
    }

    public function test_dwarf_has_minus_1_to_charisma_modifier()
    {
        $this->create_test_for_race_ability_modifier(AbstractRace::DWARF, Abilities::CHA, -1);
    }

    public function test_dwarf_doubles_con_modifier_when_adding_to_hit_points_per_level_if_positive()
    {
        $this->character->setRace(AbstractRace::DWARF);
        $this->assertEquals(7, $this->character->getMaxHp());
        $this->character->setAbility(Abilities::CON, 17); // modifier 4
        $this->assertEquals(13, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(26, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(39, $this->character->getMaxHp());
    }

    public function test_dwarf_not_doubles_con_modifier_when_adding_to_hit_points_per_level_if_negative()
    {
        $this->character->setRace(AbstractRace::DWARF);
        $this->character->setAbility(Abilities::CON, 4); // modifier -2
        $this->assertEquals(3, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(6, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(9, $this->character->getMaxHp());
    }

    public function test_dwarf_plus_2_to_attack_when_attacking_orcs()
    {
        $this->character->setRace(AbstractRace::DWARF);
        $target = new Character();
        $hits   = $this->createAttackRoll(9, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(10, $target);
        $this->assertTrue($hits);

        $target->setRace(AbstractRace::ORC);
        $hits = $this->createAttackRoll(8, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(10, $target);
        $this->assertTrue($hits);
    }

    public function test_dwarf_plus_2_to_damage_when_attacking_orcs()
    {
        $this->character->setRace(AbstractRace::DWARF);
        $target = new Character();
        $this->createAttackRoll(10, $target);
        $this->assertEquals(4, $target->getHp());

        $target = new Character();
        $target->setRace(AbstractRace::ORC);
        $this->createAttackRoll(10, $target);
        $this->assertEquals(2, $target->getHp());
    }

    protected function create_test_for_race_ability_modifier($race, $ability, $ability_change)
    {
        $human_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->character->setRace($race);
        $race_ability_modifier = $this->character->getAbilityModifier($ability);
        $this->assertEquals(($human_ability_modifier + $ability_change), $race_ability_modifier);
    }

    private function createAttackRoll($dice, $target = null)
    {
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($this->character, $target, $dice);
        return $action->attackRoll();
    }
}