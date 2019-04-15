<?php

namespace EverCraft;

/**
 * Class Alignment
 * @package EverCraft
 */
class Alignment
{
    const GOOD    = 'Good';
    const NEUTRAL = 'Neutral';
    const EVIL    = 'Evil';
    const TYPE    = [
        self::GOOD,
        self::NEUTRAL,
        self::EVIL
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

    /**
     * @param string $alignment
     *
     * @return bool
     */
    public static function isAlignmentType($alignment): bool
    {
        return in_array($alignment, self::TYPE);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;

    }
}