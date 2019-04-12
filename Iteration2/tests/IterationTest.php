<?php

namespace Tests;

use Dnd\Character;
use Dnd\CombatAction;
use Dnd\InvalidAlignmentException;

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
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 3
        $this->character->addXp(1000);
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
        // Level 4
        $this->character->addXp(1000);
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll($this->character->getLevel()));
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

    public function test_rogue_can_not_have_alignment_good()
    {
        $this->character->setClass('rogue');
        $this->expectException(InvalidAlignmentException::class);
        $this->character->setAlignment('good');
    }

    public function test_monk_hp_is_6_initially()
    {
        $this->character->setClass('monk');
        $this->assertEquals(6, $this->character->getHp());
        $this->assertEquals(6, $this->character->getMaxHp());
    }

    public function test_monk_for_each_level_hp_increase_by_6_plus_con_modifier_equals_4()
    {
        $this->character->setClass('monk');
        $this->character->setAbility('constitution', 4);
        $this->character->addXp(1000);
        $this->assertEquals(6, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(9, $this->character->getMaxHp());
    }


    public function test_monk_for_each_level_hp_increase_by_10_plus_con_modifier_equals_20()
    {
        $this->character->setClass('monk');
        $this->character->setAbility('constitution', 20);
        $this->character->addXp(1000);
        $this->assertEquals(22, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(33, $this->character->getMaxHp());
    }


    public function test_when_monk_attack_is_successful_other_character_takes_3_points_of_damage_when_hit()
    {
        $this->character->setClass('monk');
        $target = new Character();
        $this->createAttackRoll(15, $target, 0);

        $this->assertEquals(2, $target->getHp());
        $this->assertEquals(5, $target->getMaxHp());
    }

    public function test_monk_adds_positive_wisdom_and_dexterity_modifier_to_armor_class()
    {
        $this->character->setClass('monk');
        $this->character->setAbility('dexterity', 15);
        $this->character->setAbility('wisdom', 15);
        $this->assertEquals(14, $this->character->getAc());
    }

    public function test_monk_does_not_add_negative_wisdom_modifier_to_armor_class()
    {
        $this->character->setClass('monk');
        $this->character->setAbility('dexterity', 15);
        $this->character->setAbility('wisdom', 4);
        $this->assertEquals(12, $this->character->getAc());
    }

    public function test_monks_attack_rolls_increases_every_2nd_and_3rd_level()
    {
        $this->character->setClass('monk');
        $this->assertEquals(0, $this->character->getClass()->getAttackRoll(1));
        $this->assertEquals(1, $this->character->getClass()->getAttackRoll(2));
        $this->assertEquals(2, $this->character->getClass()->getAttackRoll(3));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(4));
        $this->assertEquals(3, $this->character->getClass()->getAttackRoll(5));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(6));
        $this->assertEquals(4, $this->character->getClass()->getAttackRoll(7));
        $this->assertEquals(5, $this->character->getClass()->getAttackRoll(8));
        $this->assertEquals(6, $this->character->getClass()->getAttackRoll(9));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(10));
        $this->assertEquals(7, $this->character->getClass()->getAttackRoll(11));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(12));
        $this->assertEquals(8, $this->character->getClass()->getAttackRoll(13));
        $this->assertEquals(9, $this->character->getClass()->getAttackRoll(14));
        $this->assertEquals(10, $this->character->getClass()->getAttackRoll(15));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(16));
        $this->assertEquals(11, $this->character->getClass()->getAttackRoll(17));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(18));
        $this->assertEquals(12, $this->character->getClass()->getAttackRoll(19));
        $this->assertEquals(13, $this->character->getClass()->getAttackRoll(20));
    }

    public function test_paladin_has_8_hit_points_per_level_instead_of_5()
    {
        $this->character->setClass('paladin');
        $this->assertEquals(8, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(16, $this->character->getMaxHp());
        $this->character->addXp(1000);
        $this->assertEquals(24, $this->character->getMaxHp());
        $this->character->setAbility('constitution', 18);
        $this->assertEquals(36, $this->character->getMaxHp());
    }

    public function test_paladin_plus_2_to_attack_when_attacking_evil_characters()
    {
        $this->character->setClass('paladin');
        $target = new Character();
        $hits = $this->createAttackRoll(9, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(10, $target);
        $this->assertTrue($hits);

        $target->setAlignment('evil');
        $hits = $this->createAttackRoll(7, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll(8, $target);
        $this->assertTrue($hits);
    }

    public function test_paladin_plus_2_to_damage_when_attacking_evil_characters()
    {
        $this->character->setClass('paladin');
        $target = new Character();
        $this->createAttackRoll(10, $target);
        $this->assertEquals(4, $target->getHp());

        $target = new Character();
        $target->setAlignment('evil');
        $this->createAttackRoll(8, $target);
        $this->assertEquals(2, $target->getHp());
   }

    public function test_paladins_critical_hit_is_dealt_and_the_damage_is_tripled_when_target_is_evil()
    {
        $this->character->setClass('paladin');
        $target = new Character();
        $this->createAttackRoll(20, $target, 0);

        $this->assertEquals(3, $target->getHp());

        $target = new Character();
        $target->setAlignment('evil');
        $this->createAttackRoll(20, $target, 0);

        $this->assertEquals(-4, $target->getHp());
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