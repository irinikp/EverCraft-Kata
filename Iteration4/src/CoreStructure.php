<?php


namespace EverCraft;


/**
 * Class CoreStructure
 * @package EverCraft
 */
class CoreStructure
{
    /**
     * @var bool
     */
    protected $race;
    /**
     * @var bool
     */
    protected $class;
    /**
     * @var bool
     */
    protected $weapon;
    /**
     * @var bool
     */
    protected $armor;
    /**
     * @var bool
     */
    protected $shield;
    /**
     * @var bool
     */
    protected $items;

    /**
     * CoreStructure constructor.
     *
     * @param bool $race
     * @param bool $class
     * @param bool $weapon
     * @param bool $armor
     * @param bool $shield
     * @param bool $items
     */
    public function __construct($race = true, $class = true, $weapon = true, $armor = true, $shield = true, $items = true)
    {
        $this->race   = $race;
        $this->class  = $class;
        $this->weapon = $weapon;
        $this->armor  = $armor;
        $this->shield = $shield;
        $this->items  = $items;
    }

    /**
     * @return bool
     */
    public function isRace(): bool
    {
        return $this->race;
    }

    /**
     * @param bool $race
     */
    public function setRace(bool $race): void
    {
        $this->race = $race;
    }

    /**
     * @return bool
     */
    public function isClass(): bool
    {
        return $this->class;
    }

    /**
     * @param bool $class
     */
    public function setClass(bool $class): void
    {
        $this->class = $class;
    }

    /**
     * @return bool
     */
    public function isWeapon(): bool
    {
        return $this->weapon;
    }

    /**
     * @param bool $weapon
     */
    public function setWeapon(bool $weapon): void
    {
        $this->weapon = $weapon;
    }

    /**
     * @return bool
     */
    public function isArmor(): bool
    {
        return $this->armor;
    }

    /**
     * @param bool $armor
     */
    public function setArmor(bool $armor): void
    {
        $this->armor = $armor;
    }

    /**
     * @return bool
     */
    public function isShield(): bool
    {
        return $this->shield;
    }

    /**
     * @param bool $shield
     */
    public function setShield(bool $shield): void
    {
        $this->shield = $shield;
    }

    /**
     * @return bool
     */
    public function isItems(): bool
    {
        return $this->items;
    }

    /**
     * @param bool $items
     */
    public function setItems(bool $items): void
    {
        $this->items = $items;
    }
}