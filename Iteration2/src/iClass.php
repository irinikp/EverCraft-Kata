<?php

namespace Dnd;

abstract class iClass
{
    const TYPES = [
        'Fighter' => true
    ];

    public function getAttackRoll($level)
    {
        return intval($level / 2);
    }
}