<?php

namespace EverCraft\Items\Armors;

use EverCraft\Character;

class Leather extends Armor
{

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