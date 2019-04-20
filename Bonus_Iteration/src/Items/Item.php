<?php

namespace EverCraft\Items;

use EverCraft\Character;
use EverCraft\CoreBuild;

/**
 * Class Item
 * @package EverCraft\Items
 */
abstract class Item extends CoreBuild
{

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return false;
    }

    /**
     * @param Character $character
     */
    public function wear(Character $character): void
    {
        $character->pushItem($this);
    }

    /**
     * @param Character $character
     */
    public function remove(Character $character): void
    {
        $character->removeItem($this);
    }
}