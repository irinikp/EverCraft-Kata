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
     * @var int
     */
    protected $attack_roll;
    /**
     * @var iClass
     */
    protected $class;

    /**
     * Character constructor.
     */
    public function __construct()
    {
        $this->ac          = 10;
        $this->class       = new Priest();
        $this->hp          = $this->class->getHpPerLevel();
        $this->max_hp      = $this->class->getHpPerLevel();
        $this->dead        = false;
        $this->abilities   = new Abilities();
        $this->xp          = 0;
        $this->level       = 1;
        $this->attack_roll = 0;
    }

    /**
     * @return iClass
     */
    public function getClass(): iClass
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @throws \Exception
     */
    public function setClass(string $class): void
    {
        $class = ucfirst($class);
        if (!iClass::isClassType($class)) {
            throw new \Exception("Undefined class $class");
        }
        $class       = '\Dnd\\' . $class;
        $this->class = new $class();
        $this->refreshHp();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        $class_name = get_class($this->getClass());
        return substr($class_name, strrpos($class_name, '\\') + 1);
    }

    /**
     * @return int
     */
    public function getAttackRoll(): int
    {
        return $this->attack_roll;
    }

    /**
     * @param int $attack_roll
     */
    public function setAttackRoll(int $attack_roll): void
    {
        $this->attack_roll = $attack_roll;
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
        $this->refreshAttackRoll();
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
     *
     * @throws InvalidAlignmentException
     */
    public function setAlignment($alignment): void
    {
        if ($this->getClass()->isAlignmentAllowed($alignment)) {
            $this->alignment = new Alignment($alignment);
        } else {
            throw new InvalidAlignmentException($this->getClassName() . " can't have $alignment alignment");
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
     *
     * @throws \Exception
     */
    public function setAbility($ability, $value): void
    {
        $ability = ucfirst($ability);
        if (!Abilities::isAbilityType($ability)) {
            throw new \Exception('Undefined Ability $ability');
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
        if (!Abilities::isAbilityType($ability)) {
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
    public function refreshAttackRoll(): void
    {
        $this->setAttackRoll($this->class->getAttackRoll($this->getLevel()));
    }

    /**
     * @return int
     */
    public function getAttackModifier(): int
    {
        return $this->getAbilityModifier($this->getClass()->getAttackAbility());
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
        $this->setMaxHp(max(1, ($this->getLevel()) * ($this->class->getHpPerLevel() + $modifier)));
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