<?php


namespace EverCraft\BattleGrid;


/**
 * Class Dimensions
 * @package EverCraft\BattleGrid
 */
class Dimension
{
    /**
     * @var int
     */
    protected $x;
    /**
     * @var int
     */
    protected $y;

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
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
}