<?php


namespace EverCraft\Items\Shields;

use EverCraft\CoreBuild;
use EverCraft\Items\Item;

/**
 * Class Shield
 * @package EverCraft\Items\Shields
 */
class Shield extends CoreBuild implements Item
{

    /**
     * @return bool
     */
    public function isMagical(): bool
    {
        return false;
    }
}