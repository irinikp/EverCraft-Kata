<?php

namespace EverCraft;

use EverCraft\Classes\Priest;
use EverCraft\Classes\SocialClass;
use EverCraft\Items\Armors\Armor;
use EverCraft\Items\Item;
use EverCraft\Items\Shields\Shield;
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
     * @var int
     */
    protected $damage_receiving;
    /**
     * @var Race
     */
    protected $race;
    /**
     * @var Weapon
     */
    protected $weapon;
    /**
     * @var Armor
     */
    protected $armor;
    /**
     * @var Shield
     */
    protected $shield;
    /**
     * @var array<Item>
     */
    protected $items;

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
        $this->shield    = null;
        $this->armor     = null;
        $this->items     = [];
        $this->recalculateStats();
    }

    /**
     * @return Weapon
     */
    public function getWeapon(): Weapon
    {
        return $this->weapon;
    }

    /**
     * @param Weapon $weapon
     */
    public function setWeapon(Weapon $weapon): void
    {
        $this->weapon = $weapon;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * @return Armor
     */
    public function getArmor(): Armor
    {
        return $this->armor;
    }

    /**
     * @param Armor $armor
     */
    public function setArmor(Armor $armor): void
    {
        $this->armor = $armor;
    }

    /**
     * @return Shield
     */
    public function getShield(): Shield
    {
        return $this->shield;
    }

    /**
     * @param Shield $shield
     */
    public function setShield(Shield $shield): void
    {
        $this->shield = $shield;
    }

    /**
     * @param Item $item
     *
     * @throws InvalidAlignmentException
     */
    public function use(Item $item): void
    {
        $item->wear($this);
        $this->recalculateStats();
    }

    /**
     * @param Item $item
     *
     * @throws InvalidAlignmentException
     */
    public function stopUsing(Item $item): void
    {
        $item->remove($this);
        $this->recalculateStats();
    }

    /**
     * @param Item $item
     */
    public function pushItem(Item $item): void
    {
        array_push($this->items, $item);
    }

    /**
     * @param Item $item
     */
    public function removeItem(Item $item): void
    {
        $key = array_search($item, $this->items);
        unset($this->items[$key]);
    }


    /**
     * @return int
     */
    public function getDamageReceiving(): int
    {
        return $this->damage_receiving;
    }

    /**
     * @param int $damage_receiving
     */
    public function setDamageReceiving(int $damage_receiving): void
    {
        $this->damage_receiving = $damage_receiving;
    }

    /**
     * @param $object
     *
     * @return string
     */
    public function getObjectName($object): string
    {
        $name = '';
        if ($this->$object) {
            $name = $this->getObjectClassNameWithoutNamespace($this->$object);
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
        if ($target) {
            $attack_bonus_on_this_target += $this->callFunctionTree('getAttackRoll', [$this->getLevel(), $this, $target], new CoreStructureCaller());
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
        if ($target) {
            $damage += $this->callFunctionTree('getDamageModifierWhenAttacking', [$this, $target], new CoreStructureCaller());
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
        if (!in_array($race, Race::RACE_TYPES)) {
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
        if (!in_array($class, SocialClass::CLASS_TYPES)) {
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
        if (!in_array($ability, Abilities::TYPE)) {
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
        if (!in_array($ability, Abilities::TYPE)) {
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
        if ($attacker) {
            $ac += $this->callFunctionTree('getAcModifierWhenUnderAttack', [$this, $attacker], new CoreStructureCaller());
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
        return $this->callFunctionTree('getCriticalDamageMultiplier', [$target], new CoreStructureCaller());
    }

    /**
     * @param Armor $armor
     */
    public function wearArmor(Armor $armor): void
    {
        if ($armor->isAllowedToWear($this)) {
            $this->armor = $armor;
        }
    }

    /**
     * @param string              $function
     * @param array               $args
     * @param CoreStructureCaller $call
     *
     * @return int
     */
    protected function callFunctionTree($function, array $args, CoreStructureCaller $call): int
    {
        $output = 0;
        if ($call->callClass()) {
            $output += call_user_func_array([$this->getClass(), $function], $args);
        }
        if ($call->callRace()) {
            $output += call_user_func_array([$this->getRace(), $function], $args);
        }
        if ($call->callWeapon() && $this->weapon) {
            $output += call_user_func_array([$this->getWeapon(), $function], $args);
        }
        if ($call->callArmor() && $this->armor) {
            $output += call_user_func_array([$this->getArmor(), $function], $args);
        }
        if ($call->callShield() && $this->shield) {
            $output += call_user_func_array([$this->getShield(), $function], $args);
        }
        if ($call->callItems()) {
            foreach ($this->items as $item) {
                $output += call_user_func_array([$item, $function], $args);
            }
        }
        return $output;
    }

    /**
     *
     */
    protected function recalculateAttackBonus(): void
    {
        $attack_bonus = $this->getAbilityModifier($this->getClass()->getAttackAbility());
        $this->setAttackBonus($attack_bonus);
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
        $this->recalculateDamageReceiving();

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
            $this->callFunctionTree('getAcModifier', [$this], new CoreStructureCaller())
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
        return $this->callFunctionTree('getHpModifier', [$this], new CoreStructureCaller());
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
        if ($this->weapon) {
            $this->setDamage($this->callFunctionTree('getDamage', [$this], new CoreStructureCaller(false, false)));
        } else {
            $this->setDamage(
                $this->callFunctionTree('getDamage', [$this], new CoreStructureCaller(true, true, false, false, false, false))
            );
        }
    }

    /**
     *
     */
    protected function recalculateDamageReceiving(): void
    {
        $this->setDamageReceiving($this->callFunctionTree('getDamageReceiving', [], new CoreStructureCaller()));
    }

    /**
     * @param object $object
     *
     * @return string
     */
    protected function getObjectClassNameWithoutNamespace($object): string
    {
        $class_name = get_class($object);
        return substr($class_name, strrpos($class_name, '\\') + 1);
    }
}