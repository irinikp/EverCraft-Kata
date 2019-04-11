<?php

namespace Dnd;

/**
 * Class iClass
 * @package Dnd
 */
abstract class iClass
{
    const TYPES = [
        'Fighter' => true
    ];

    /**
     * @param int $level
     *
     * @return int
     */
    public function getAttackRoll($level)
    {
        return intval($level / 2);
    }

    /**
     * @return int
     */
    public function getHpPerLevel()
    {
        return 5;
    }
}