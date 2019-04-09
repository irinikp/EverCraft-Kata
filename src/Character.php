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
     * @var int
     */
    protected $xp;
    /**
     * @var int
     */
    protected $level;

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
        $this->xp        = 0;
        $this->level     = 1;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @param int $level
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;
        $this->refreshHp();
    }

    /**
     * @return int
     */
    public function getXp(): int
    {
        return $this->xp;
    }

    /**
     * @param int $xp
     */
    public function setXp(int $xp): void
    {
        $this->xp = $xp;
        $this->refreshLevel();
    }

    /**
     * @param int $xp
     */
    public function addXp(int $xp): void
    {
        $this->setXp($this->getXp() + $xp);
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
    public function refreshDeathStatus(): void
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
            $this->refreshAc();
        }
        if ('Constitution' === $ability) {
            $this->refreshHp();
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
        $this->refreshDeathStatus();
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
    public function takeDamage($damage): void
    {
        $this->setHp($this->getHp() - $damage);
    }

    /**
     *
     */
    public function gainSuccessfulAttackXp(): void
    {
        $this->setXp($this->getXp() + 10);
    }

    /**
     *
     */
    protected function refreshAc(): void
    {
        $modifier = $this->getAbilityModifier('dexterity');
        $this->setAc($this->getAc() + $modifier);
    }

    /**
     *
     */
    protected function refreshHp(): void
    {
        $modifier = $this->getAbilityModifier('constitution');
        $this->setMaxHp(max(1, $this->getMaxHp() + $modifier));
        $this->setHp($this->getMaxHp());
    }

    /**
     *
     */
    protected function refreshLevel(): void
    {
        $this->setLevel(intval($this->getXp() / 1000) + 1);
    }
}