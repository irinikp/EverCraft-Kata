<?php

namespace EverCraft\BattleGrid;

/**
 * Class Terrain
 * @package EverCraft\BattleGrid
 */
class Terrain
{
    const NORMAL    = 0;
    const HIGH      = 1;
    const LOW       = -1;
    const DIFFICULT = 'Difficult';

    /**
     * @var int
     */
    protected $height;
    /**
     * @var string
     */
    protected $quality;

    /**
     * Terrain constructor.
     */
    public function __construct()
    {
        $this->height  = self::NORMAL;
        $this->quality = self::NORMAL;
    }

    /**
     * @return string
     */
    public function getQuality(): string
    {
        return $this->quality;
    }

    /**
     * @param string $quality
     */
    public function setQuality(string $quality): void
    {
        $this->quality = $quality;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }


}