<?php

namespace EverCraft;

use EverCraft\Classes\Priest;
use EverCraft\Classes\SocialClass;
use EverCraft\Items\Weapons\Weapon;
use EverCraft\Races\Human;
use EverCraft\Races\Race;

/**
 * Class Character
 * @package EverCraft
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
     * @var SocialClass
     */
    protected $class;
    /**
     * @var int
     */
    protected $attack_bonus;
    /**
     * @var int
     */
    protected $damage;
    /**
     * @var Race
     */
    protected $race;
    /**
     * @var Weapon
     */
    protected $weapon;

    /**
     * Character constructor.
     */
    public function __construct()
    {
        $this->ac        = 10;
        $this->class     = new Priest();
        $this->hp        = $this->class->getHpPerLevel();
        $this->max_hp    = $this->class->getHpPerLevel();
        $this->dead      = false;
        $this->abilities = new Abilities();
        $this->xp        = 0;
        $this->level     = 1;
        $this->alignment = Alignment::NEUTRAL;
        $this->race      = new Human();
        $this->weapon    = null;
        $this->recalculateStats();
    }

    /**
     * @return Weapon|null
     */
    public function getWeapon(): Weapon
    {
        return $this->weapon;
    }

    /**
     * @return string
     */
    public function getWeaponName(): string
    {
        if ($this->wieldsWeapon()) {
            return $this->getObjectClassNameWithoutNamespace($this->weapon);
        }
    }

    /**
     * @param Weapon $weapon
     */
    public function wield(Weapon $weapon): void
    {
        $this->weapon = $weapon;
        $this->recalculateStats();
    }

    /**
     * @return string
     */
    public function getWieldingWeaponName(): string
    {
        $name = '';
        if ($this->wieldsWeapon()) {
            $name = $this->getObjectClassNameWithoutNamespace($this->weapon);
        }
        return $name;
    }

    /**
     * @param Character|null $target
     *
     * @return int
     */
    public function getAttackBonus(Character $target = null): int
    {
        $attack_bonus_on_this_target = 0;
        if (null !== $target) {
            $attack_bonus_on_this_target += $this->getClass()->getAttackRoll($this->getLevel(), 0, $target)
                + $this->getRace()->getAttackRoll($this->getLevel(), 0, $target);
        }
        return $attack_bonus_on_this_target + $this->attack_bonus;
    }

    /**
     * @param int $attack_bonus
     */
    public function setAttackBonus(int $attack_bonus): void
    {
        $this->attack_bonus = $attack_bonus;
    }

    /**
     * @param Character|null $target
     *
     * @return int
     */
    public function getDamage(Character $target = null): int
    {
        $damage = $this->damage;
        if (null !== $target) {
            $damage += $this->getClass()->getDamageModifierWhenAttacking($target) +
                $this->getRace()->getDamageModifierWhenAttacking($target);
        }
        return $damage;
    }

    /**
     * @param int $damage
     */
    public function setDamage(int $damage): void
    {
        $this->damage = $damage;
    }

    /**
     * @return Race
     */
    public function getRace(): Race
    {
        return $this->race;
    }

    /**
     * @param string $race
     *
     * @throws \Exception
     */
    public function setRace(string $race): void
    {
        $race = ucfirst($race);
        if (!Race::belongs($race)) {
            throw new \Exception("Undefined race $race");
        }
        $race       = '\EverCraft\\Races\\' . $race;
        $this->race = new $race();
        $this->recalculateStats();
    }

    /**
     * @return string
     */
    public function getRaceName(): string
    {
        return $this->getObjectClassNameWithoutNamespace($this->getRace());
    }

    /**
     * @return SocialClass
     */
    public function getClass(): SocialClass
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
        if (!SocialClass::belongs($class)) {
            throw new \Exception("Undefined class $class");
        }
        $class       = '\EverCraft\\Classes\\' . $class;
        $this->class = new $class();
        $this->recalculateStats();
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->getObjectClassNameWithoutNamespace($this->getClass());
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
     *
     * @throws InvalidAlignmentException
     */
    public function setLevel(int $level): void
    {
        $this->level = $level;

        $this->recalculateStats();
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
        $this->recalculateLevel();
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
        $alignment = ucfirst($alignment);
        if ($this->isAlignmentAllowed($alignment)) {
            $this->alignment = new Alignment($alignment);
        } else {
            throw new InvalidAlignmentException($this->getClassName() . " can't have $alignment alignment");
        }
    }

    /**
     *
     */
    public function recalculateDeathStatus(): void
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

        $this->recalculateStats();
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
        $function = 'get' . $ability . 'Modifier';
        return $this->getRace()->$function($this);
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
     * @param Character|null $attacker
     *
     * @return int
     */
    public function getAc(Character $attacker = null): int
    {
        $ac = $this->ac;
        if (null !== $attacker) {
            $ac += $this->getClass()->getAcModifierWhenUnderAttack($this, $attacker) +
                $this->getRace()->getAcModifierWhenUnderAttack($this, $attacker);
        }
        return $ac;
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
        $this->recalculateDeathStatus();
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
     * @param $dice
     *
     * @return bool
     */
    public function isCritical($dice): bool
    {
        return $this->getRace()->isCritical($dice);
    }

    /**
     * @param Character $target
     *
     * @return int
     */
    public function getCriticalDamageMultiplier(Character $target): int
    {
        $multiplier = $this->getClass()->getCriticalDamageMultiplier($target) + $this->getRace()->getCriticalDamageMultiplier($target);
        if ($this->wieldsWeapon()) {
            $multiplier += $this->getWeapon()->getCriticalDamageMultiplier($target);
        }
        return $multiplier;
    }

    /**
     *
     */
    protected function recalculateAttackBonus(): void
    {
        $attack_bonus = $this->getAbilityModifier($this->getClass()->getAttackAbility());
        if ($this->wieldsWeapon()) {
            $attack_bonus += $this->weapon->getAttackRoll($this->getLevel());
        }
        $this->setAttackBonus($attack_bonus);
    }

    protected function wieldsWeapon(): bool
    {
        return null !== $this->weapon;
    }

    /**
     * @throws InvalidAlignmentException
     */
    protected function recalculateStats(): void
    {
        $this->recalculateAc();
        $this->recalculateHp();
        $this->recalculateAlignment();
        $this->recalculateAttackBonus();
        $this->recalculateDamage();

    }

    /**
     * @param string $alignment
     *
     * @return bool
     */
    protected function isAlignmentAllowed($alignment): bool
    {
        return $this->getClass()->isAlignmentAllowed($alignment) && $this->getRace()->isAlignmentAllowed($alignment);
    }

    /**
     * @throws InvalidAlignmentException
     */
    protected function recalculateAlignment(): void
    {
        if (!$this->getClass()->isAlignmentAllowed($this->getAlignment())) {
            $this->setAlignment($this->getClass()->getAllowedAlignments()[0]);
        }
    }

    /**
     *
     */
    protected function recalculateAc(): void
    {
        $this->setAc(
            $this->getClass()->getBasicAc() +
            $this->getAbilityModifier(Abilities::DEX) +
            $this->getClass()->getAcModifier($this) +
            $this->getRace()->getAcModifier($this)
        );
    }

    /**
     *
     */
    protected function recalculateHp(): void
    {
        $modifier = $this->getHpModifier();
        $damage   = $this->getMaxHp() - $this->getHp();
        $this->setMaxHp(max(1, ($this->getLevel()) * ($this->class->getHpPerLevel() + $modifier)));
        $this->setHp($this->getMaxHp() - $damage);
    }

    /**
     * @return int
     */
    protected function getHpModifier(): int
    {
        return $this->getClass()->getHpModifier($this) + $this->getRace()->getHpModifier($this);;
    }

    /**
     *
     */
    protected function recalculateLevel(): void
    {
        $this->setLevel(intval($this->getXp() / 1000) + 1);
    }

    /**
     *
     */
    protected function recalculateDamage(): void
    {
        if ($this->wieldsWeapon()) {
            $this->setDamage($this->getWeapon()->getDamage($this));
        } else {
            $this->setDamage($this->getClass()->getDamage($this) + $this->getRace()->getDamage($this));
        }
    }

    /**
     * @param object $object
     *
     * @return string
     */
    private function getObjectClassNameWithoutNamespace($object): string
    {
        $class_name = get_class($object);
        return substr($class_name, strrpos($class_name, '\\') + 1);
    }
}