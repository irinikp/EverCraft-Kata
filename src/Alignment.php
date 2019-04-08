<?php

namespace Dnd;

/**
 * Class Allignment
 * @package Dnd
 */
class Alignment
{
    const GOOD    = 'GOOD';
    const NEUTRAL = 'NEUTRAL';
    const EVIL    = 'EVIL';

    /**
     * @var string
     */
    protected $value;

    /**
     * Allignment constructor.
     *
     * @param string $allignment
     *
     * @throws \Exception
     */
    public function __construct($allignment)
    {
        if ($allignment !== self::GOOD && $allignment !== self::NEUTRAL && $allignment !== self::EVIL) {
            throw new \Exception('Allignment value is not valid');
        }
        $this->value = $allignment;
    }

    public function __toString()
    {
        return $this->value;

    }
}