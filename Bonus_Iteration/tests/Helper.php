<?php

namespace Tests;

use EverCraft\Battle\CombatAction;
use EverCraft\Map\BattleGrid;
use EverCraft\Map\CartesianPoint;
use EverCraft\Character;
use EverCraft\InvalidAlignmentException;

/**
 * Class Helper
 * @package Tests
 */
class Helper extends \PHPUnit\Framework\TestCase
{

    /**
     * @param Character $attacker
     * @param Character $target
     * @param int       $dice
     *
     * @throws \Exception
     */
    public function assert_attacker_hits_with_roll(Character $attacker, Character $target, int $dice): void
    {
        $battle_grid   = $this->initiateBattleGrid($attacker, $target);
        $attack_action = new CombatAction(CombatAction::ATTACK);
        $attack_action->setUpAttack($attacker, $target, $dice - 1, $battle_grid);
        $this->assertFalse($attack_action->attackRoll());
        $attack_action->setUpAttack($attacker, $target, $dice, $battle_grid);
        $this->assertTrue($attack_action->attackRoll());
    }

    /**
     * @param Character $character
     * @param string    $weapon
     * @param int       $damage
     * @param int       $magical
     * @param Character $attacker
     * @param int       $dice
     *
     * @throws InvalidAlignmentException
     * @throws \Exception
     */
    public function assert_damage_of_weapon(Character $character, $weapon, $damage, $magical, $attacker, $dice = 10)
    {
        $weapon_class = '\\EverCraft\\Items\\Weapons\\' . $weapon;
        $character->use(new $weapon_class($magical));
        $target = new Character();
        $this->createAttackRoll($character, $dice, $target);
        $this->assert_has_remaining_hp(5 - $damage, $target);
    }

    /**
     * @param Character      $attacker
     * @param int            $dice
     * @param Character|null $target
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function createAttackRoll(Character $attacker, $dice, ?Character $target = null): bool
    {
        if (null === $target) {
            $target = new Character();
        }

        $attack_action = new CombatAction(CombatAction::ATTACK);
        $attack_action->setUpAttack($attacker, $target, $dice, $this->initiateBattleGrid($attacker, $target));
        return $attack_action->attackRoll();
    }

    /**
     * @param int       $hp
     * @param Character $character
     */
    public function assert_has_remaining_hp($hp, $character): void
    {
        $this->assertEquals($hp, $character->getHp());
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @param int       $dice
     *
     * @return bool
     * @throws \Exception
     */
    public function createCounterAttackRoll(Character $attacker, Character $target, int $dice): bool
    {
        $attack_action = new CombatAction(CombatAction::ATTACK);
        $attack_action->setUpAttack($target, $attacker, $dice, $this->initiateBattleGrid($target, $attacker));
        return $attack_action->attackRoll();
    }

    /**
     * @param Character $character
     * @param Character $defender
     *
     * @param int       $dice
     *
     * @throws \Exception
     */
    public function assert_defender_hits_with_roll(Character $character, Character $defender, $dice): void
    {
        $hits = $this->createCounterAttackRoll($character, $defender, $dice - 1);
        $this->assertFalse($hits);
        $hits = $this->createCounterAttackRoll($character, $defender, $dice);
        $this->assertTrue($hits);
    }

    /**
     * @param Character $character
     * @param string    $race
     * @param string    $ability
     * @param int       $ability_change
     *
     * @throws \Exception
     */
    public function create_test_for_race_ability_modifier(Character $character, $race, $ability, $ability_change): void
    {
        $human_ability_modifier = $character->getAbilityModifier($ability);
        $character->setRace($race);
        $race_ability_modifier = $character->getAbilityModifier($ability);
        $this->assertEquals(($human_ability_modifier + $ability_change), $race_ability_modifier);
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return BattleGrid
     */
    protected function initiateBattleGrid(Character $attacker, Character $target): BattleGrid
    {
        $battle_grid = new BattleGrid();
        $battle_grid->setDimensions(10, 10);
        $battle_grid->place($attacker, new CartesianPoint(0, 0));
        $battle_grid->place($target, new CartesianPoint(0, 1));
        return $battle_grid;
    }

}