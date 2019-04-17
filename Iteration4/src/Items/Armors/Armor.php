<?php

namespace EverCraft\Items\Armors;


use EverCraft\Character;
use EverCraft\CoreBuild;
use EverCraft\Items\Item;

/**
 * Class Armor
 * @package EverCraft\Items\Armors
 */
class Armor extends CoreBuild implements Item
{

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function isAllowedToWear(Character $character): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return false;
    }
}