<?php

namespace EverCraft\Items\Armors;

use EverCraft\Character;
use EverCraft\Items\Item;

/**
 * Class Armor
 * @package EverCraft\Items\Armors
 */
class Armor extends Item
{

    /**
     * @param Character $character
     */
    public function wear(Character $character): void
    {
        $character->wearArmor($this);
    }

    /**
     * @param Character $character
     */
    public function remove(Character $character): void
    {
        $character->setArmor(null);
    }

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function isAllowedToWear(Character $character): bool
    {
        return true;
    }
}