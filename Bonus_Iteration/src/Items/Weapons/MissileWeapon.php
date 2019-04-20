<?php


namespace EverCraft\Items\Weapons;


/**
 * Class MissileWeapon
 * @package EverCraft\Items\Weapons
 */
class MissileWeapon extends Weapon
{
    /**
     * MissileWeapon constructor.
     *
     * @param int $magical
     */
    public function __construct($magical = 0)
    {
        parent::__construct($magical);
        $this->min_range = 2;
        $this->max_range = 20;
    }

    /**
     * @return bool
     */
    public function isMissile()
    {
        return true;
    }

}