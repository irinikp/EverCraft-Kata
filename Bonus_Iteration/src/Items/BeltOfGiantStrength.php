<?php

namespace EverCraft\Items;

use EverCraft\Abilities;
use EverCraft\Character;

/**
 * Class BeltOfGiantStrength
 * @package EverCraft\Items
 */
class BeltOfGiantStrength extends Item
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
     * @throws \Exception
     */
    public function wear(Character $character): void
    {
        parent::wear($character);
        $character->setAbility(Abilities::STR, ($character->getAbilities()->getStrength() + 4));
    }

    /**
     * @param Character $character
     *
     * @throws \Exception
     */
    public function remove(Character $character): void
    {
        parent::remove($character);
        $character->setAbility(Abilities::STR, ($character->getAbilities()->getStrength() - 4));
    }

}