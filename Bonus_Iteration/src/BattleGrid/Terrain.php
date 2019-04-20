<?php

namespace EverCraft\BattleGrid;

/**
 * Class Terrain
 * @package EverCraft\BattleGrid
 */
class Terrain
{
    const NORMAL    = 'Normal';
    const HIGH      = 'High';
    const LOW       = 'Low';
    const DIFFICULT = 'Difficult';

    /**
     * @var string
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
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight(string $height): void
    {
        $this->height = $height;
    }


}