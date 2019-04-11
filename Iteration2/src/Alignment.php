<?php

namespace Dnd;

/**
 * Class Alignment
 * @package Dnd
 */
class Alignment
{
    const TYPE = [
        'Good'    => true,
        'Neutral' => true,
        'Evil'    => true
    ];

    /**
     * @var string
     */
    protected $value;

    /**
     * Alignment constructor.
     *
     * @param string $alignment
     *
     * @throws InvalidAlignmentException
     */
    public function __construct($alignment)
    {
        $alignment = ucfirst($alignment);
        if (!self::isAlignmentType($alignment)) {
            throw new InvalidAlignmentException('Undefined Alignment $alignment');
        }
        $this->value = $alignment;
    }

    public static function isAlignmentType($alignment): bool
    {
        return array_key_exists($alignment, self::TYPE);
    }

    public function __toString()
    {
        return $this->value;

    }
}