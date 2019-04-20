<?php


namespace EverCraft\BattleGrid;


/**
 * Class Dimensions
 * @package EverCraft\BattleGrid
 */
class CartesianPoint
{
    const X_AXIS = 'X';
    const Y_AXIS = 'Y';
    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;

    /**
     * CartesianPoint constructor.
     *
     * @param int $x
     * @param int $y
     */
    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @param CartesianPoint        $initial_position
     * @param array<CartesianPoint> $route
     *
     * @return bool
     */
    public static function isStraightLine($initial_position, $route): bool
    {
        $current_position = $initial_position;
        foreach ($route as $spot) {
            if ($current_position->getX() !== $spot->getX() && $current_position->getY() !== $spot->getY()) {
                return false;
            }
            $current_position = $spot;
        }
        return true;
    }

    /**
     * @param string $axis
     *
     * @return string
     */
    public static function getVerticalAxis($axis): string
    {
        if (self::X_AXIS === $axis) return self::Y_AXIS;
        else return self::X_AXIS;
    }

    /**
     * @return int
     */
    public function getX(): int
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX(int $x): void
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY(): int
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY(int $y): void
    {
        $this->y = $y;
    }

    /**
     * @param int            $distance
     * @param CartesianPoint $point
     *
     * @return bool
     */
    public function isFurtherThan(int $distance, CartesianPoint $point)
    {
        return $distance <= ($this->getDistance($point));
    }

    /**
     * @param CartesianPoint $point
     *
     * @return float
     */
    public function getDistance(CartesianPoint $point)
    {
        return sqrt(pow($point->getX() - $this->getX(), 2) + pow($point->getY() - $this->getY(), 2));
    }

    /**
     * @param int            $distance
     * @param CartesianPoint $point
     *
     * @return bool
     */
    public function isCloserThan(int $distance, CartesianPoint $point)
    {
        return $distance >= floor($this->getDistance($point));
    }
}