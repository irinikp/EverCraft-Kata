<?php

namespace EverCraft\Items\Armors;


/**
 * Class LeatherOfDamageReduction
 * @package EverCraft\Items\Armors
 */
class LeatherOfDamageReduction extends Leather
{

    /**
     * @return int
     */
    public function getDamageReceiving(): int
    {
        return -2;
    }

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return true;
    }

}