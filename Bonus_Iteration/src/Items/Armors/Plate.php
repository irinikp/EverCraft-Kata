<?php

namespace EverCraft\Items\Armors;

use EverCraft\Character;
use EverCraft\Classes\SocialClass;
use EverCraft\Races\Race;

class Plate extends Armor
{

    /**
     * @param Character $character
     *
     * @return bool
     */
    public function isAllowedToWear(Character $character): bool
    {
        return (SocialClass::FIGHTER === $character->getClassName() || Race::DWARF === $character->getRaceName());
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return 8;
    }

}