<?php

namespace Dnd;

/**
 * Class Alignment
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
     * @param string $alignment
     *
     * @throws \Exception
     */
    public function __construct($alignment)
    {
        if ($alignment !== self::GOOD && $alignment !== self::NEUTRAL && $alignment !== self::EVIL) {
            throw new \Exception('Alignment value is not valid');
        }
        $this->value = $alignment;
    }

    public function __toString()
    {
        return $this->value;

    }
}