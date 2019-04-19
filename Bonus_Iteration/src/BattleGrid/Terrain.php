<?php


namespace EverCraft\BattleGrid;


class Terrain
{
    const NORMAL = 'Normal';
    const HIGH = 'High';
    const LOW = 'Low';
    protected $height;

    public function __construct()
    {
        $this->height = self::NORMAL;
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