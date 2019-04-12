<?php

use Dnd\Character;

/**
 * Trait GlobalCharacteristics
 */
trait GlobalCharacteristics
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public static function belongs(string $type): bool
    {
        return in_array($type, self::TYPES);
    }

    /**
     * @param Character $character
     *
     * @return int
     */
    public function getAcModifier(Character $character): int
    {
        return 0;
    }
}