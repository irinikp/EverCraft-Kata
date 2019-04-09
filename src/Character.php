<?php

namespace Dnd;

/**
 * Class Character
 * @package Dnd
 */
class Character
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Alignment
     */
    protected $alignment;

    /**
     * @var int
     */
    protected $ac;
    /**
     * @var int
     */
    protected $hp;
    /**
     * @var int
     */
    protected $max_hp;

    /**
     * @var bool
     */
    protected $dead;

    /**
     * @var Abilities
     */
    protected $abilities;

    /**
     * Character constructor.
     */
    public function __construct()
    {
        $this->ac        = 10;
        $this->hp        = 5;
        $this->max_hp    = 5;
        $this->dead      = false;
        $this->abilities = new Abilities();
    }

    /**
     * @return int
     */
    public function getMaxHp(): int
    {
        return $this->max_hp;
    }

    /**
     * @param int $max_hp
     */
    public function setMaxHp(int $max_hp): void
    {
        $this->max_hp = $max_hp;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAlignment(): string
    {
        return $this->alignment;
    }

    /**
     * @param string $alignment
     */
    public function setAlignment($alignment): void
    {
        try {
            $this->alignment = new Alignment($alignment);
        } catch (\Exception $e) {
            $this->alignment = '';
        }
    }

    /**
     *
     */
    public function setDearOrAlive(): void
    {
        if ($this->hp <= 0) {
            $this->setDead(true);
        } else {
            $this->setDead(false);
        }
    }

    /**
     * @return bool
     */
    public function isDead(): bool
    {
        return $this->dead;
    }

    /**
     * @param bool $dead
     */
    public function setDead(bool $dead): void
    {
        $this->dead = $dead;
    }

    /**
     * @param string $ability
     * @param int    $value
     */
    public function setAbility($ability, $value): void
    {
        $ability = ucfirst($ability);
        if (!Abilities::NAME[$ability]) {
            return;
        }
        $function = "set$ability";
        $this->abilities->$function($value);

        if ('Dexterity' === $ability) {
            $this->adjustAcFromDexterity();
        }
        if ('Constitution' === $ability) {
            $this->adjustHpFromConstitution();
        }
    }

    /**
     * @param string $ability
     *
     * @return int
     */
    public function getAbilityModifier($ability): int
    {
        $ability = ucfirst($ability);
        if (!Abilities::NAME[$ability]) {
            return 0;
        }
        $function = "get$ability";
        return Abilities::MODIFIER[$this->getAbilities()->$function()];
    }

    /**
     * @return Abilities
     */
    public function getAbilities(): Abilities
    {
        return $this->abilities;
    }

    /**
     * @param Abilities $abilities
     */
    public function setAbilities(Abilities $abilities): void
    {
        $this->abilities = $abilities;
    }

    /**
     * @return int
     */
    public function getAc(): int
    {
        return $this->ac;
    }

    /**
     * @param int $ac
     */
    public function setAc($ac): void
    {
        $this->ac = $ac;
    }

    /**
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
        $this->setDearOrAlive();
    }

    /**
     * @param int $dice
     *
     * @return int
     */
    public function roll($dice): int
    {
        return rand(1, $dice);
    }

    /**
     * @param int $damage
     */
    public function damage($damage): void
    {
        $this->setHp($this->getHp() - $damage);
    }

    protected function adjustAcFromDexterity(): void
    {
        $modifier = $this->getAbilityModifier('dexterity');
        $this->setAc($this->getAc() + $modifier);
    }

    protected function adjustHpFromConstitution(): void
    {
        $modifier = $this->getAbilityModifier('constitution');
        $this->setHp(max(1, $this->getHp() + $modifier));
    }
}