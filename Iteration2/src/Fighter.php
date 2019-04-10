<?php

namespace Dnd;

class Fighter extends iClass
{
    /**
     *
     */
    public function getAttackRoll($level)
    {
        return $level - 1;
    }

}