<?php

namespace EverCraft\Items;

use EverCraft\Character;

/**
 * Class RingOfProtection
 * @package EverCraft\Items
 */
class RingOfProtection extends Item
{
    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return true;
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return 2;
    }
}