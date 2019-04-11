<?php

namespace Tests;

use Dnd\Character;
use Dnd\CombatAction;

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

    public function test_fighter_attack_roll_increases_by_1_in_every_level()
    {
        $this->character->setClass('fighter');
        // Level 2
        $this->character->addXp(1000);
        $this->assertEquals(1, $this->character->getAttackRoll());
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getAttackRoll());
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getAttackRoll());
    }

    public function test_fighter_hp_is_10_initially()
    {
        $this->character->setClass('fighter');
        $this->assertEquals(10, $this->character->getHp());
        $this->assertEquals(10, $this->character->getMaxHp());
    }

    public function test_fighter_for_each_level_hp_increase_by_10_plus_con_modifier_equals_4()
    {
        $this->character->setClass('fighter');
        $this->character->setAbility('constitution', 4);
        $this->character->addXp(1000);
        $this->assertEquals(14, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(21, $this->character->getMaxHp());
    }


    public function test_fighter_for_each_level_hp_increase_by_10_plus_con_modifier_equals_20()
    {
        $this->character->setClass('fighter');
        $this->character->setAbility('constitution', 20);
        $this->character->addXp(1000);
        $this->assertEquals(30, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(45, $this->character->getMaxHp());
    }

    public function test_rogues_critical_hit_is_dealt_and_the_damage_is_tripled()
    {
        $this->character->setClass('rogue');
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);

        $this->assertEquals(2, $target->getHp());
    }

    public function test_rogues_strength_modifier_does_not_apply_to_attack()
    {
        $this->character->setAbility('strength', 2);
        $this->character->setClass('rogue');
        $hits = $this->createAttackRoll(9);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(10);
        $this->assertTrue($hits);
    }

    public function test_rogues_dexterity_modifier_applies_to_attack()
    {
        $this->character->setAbility('dexterity', 12);
        $this->character->setClass('rogue');
        $hits = $this->createAttackRoll(8);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(9);
        $this->assertTrue($hits);
    }

    public function test_dexterity_modifier_of_target_is_ignored_if_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility('dexterity', 15);
        $this->character->setAbility('dexterity', 2);
        $this->character->setClass('rogue');
        $hits = $this->createAttackRoll(13, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(14, $target);
        $this->assertTrue($hits);
    }

    public function test_dexterity_modifier_of_target_is_not_ignored_if_not_positive_when_attacked_by_rogue()
    {
        $target = new Character();
        $target->setAbility('dexterity', 6);
        $this->character->setAbility('dexterity', 2);
        $this->character->setClass('rogue');
        $hits = $this->createAttackRoll(11, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(12, $target);
        $this->assertTrue($hits);
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