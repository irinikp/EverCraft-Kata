<?php


namespace Tests;


use EverCraft\Character;
use EverCraft\CombatAction;
use EverCraft\InvalidAlignmentException;

/**
 * Class Helper
 * @package Tests
 */
class Helper extends \PHPUnit\Framework\TestCase
{

    /**
     * @param Character $character
     * @param int       $dice
     * @param Character $target
     */
    public function assert_attacker_hits_with_roll(Character $character, $dice, $target): void
    {
        $hits = $this->createAttackRoll($character, $dice - 1, $target);
        $this->assertFalse($hits);
        $hits = $this->createAttackRoll($character, $dice, $target);
        $this->assertTrue($hits);
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
     * @param Character      $character
     * @param int            $dice
     * @param Character|null $target
     *
     * @return bool
     */
    public function createAttackRoll(Character $character, $dice, $target = null): bool
    {
        if (null === $target) {
            $target = new Character();
        }

        $action = new CombatAction($character, $target, $dice);
        return $action->attackRoll();
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
     * @param Character $character
     * @param int       $dice
     * @param Character $target
     *
     * @return bool
     */
    public function createCounterAttackRoll(Character $character, $dice, $target): bool
    {
        $action = new CombatAction($target, $character, $dice);
        return $action->attackRoll();
    }

    /**
     * @param Character $character
     * @param int       $dice
     * @param Character $defender
     */
    public function assert_defender_hits_with_roll(Character $character, $dice, $defender): void
    {
        $hits = $this->createCounterAttackRoll($character, $dice - 1, $defender);
        $this->assertFalse($hits);
        $hits = $this->createCounterAttackRoll($character, $dice, $defender);
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

}