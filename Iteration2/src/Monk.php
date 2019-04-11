<?php

namespace Dnd;

/**
 * Class Monk
 * @package Dnd
 */
class Monk extends iClass
{

    /**
     * @return int
     */
    public function getHpPerLevel(): int
    {
        return 6;
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return 3;
    }
}