<?php

trait GlobalCharacteristics
{
    public static function belongs(string $type): bool
    {
        return in_array($type, self::TYPES);
    }
}