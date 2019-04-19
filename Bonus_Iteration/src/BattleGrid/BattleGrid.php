<?php


namespace EverCraft\BattleGrid;


/**
 * Class BattleGrid
 * @package EverCraft\BattleGrid
 */
class BattleGrid
{
    /**
     * @var Dimensions
     */
    protected $dimensions;
    /**
     * @var array<Terrain, Terrain>
     */
    protected $map;

    /**
     * BattleGrid constructor.
     */
    public function __construct()
    {
        $this->map = [];
    }

    /**
     * @return Dimensions
     */
    public function getDimensions(): Dimensions
    {
        return $this->dimensions;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * It recreates the whole Battle Field and removes old setting
     */
    public function setDimensions($x, $y): void
    {
        $this->dimensions = new Dimensions($x, $y);
        $this->setTerrain($x, $y);
    }

    /**
     * @param string     $height
     * @param Dimensions $from
     * @param Dimensions $to
     */
    public function setTerrainHeight($height, Dimensions $from, Dimensions $to): void
    {
        $this->setTerrainCharacteristic($height, 'Height', $from, $to);
    }

    /**
     * @param string     $quality
     * @param Dimensions $from
     * @param Dimensions $to
     */
    public function setTerrainQuality($quality, Dimensions $from, Dimensions $to): void
    {
        $this->setTerrainCharacteristic($quality, 'Quality', $from, $to);
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    public function getTerrainHeight($x, $y): string
    {
        return $this->map[$x][$y]->getHeight();
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    public function getTerrainQuality($x, $y): string
    {
        return $this->map[$x][$y]->getQuality();
    }

    /**
     * @param int $x
     * @param int $y
     */
    protected function setTerrain($x, $y): void
    {
        $this->map = [array_fill(0, 20, new Terrain()), array_fill(0, 20, new Terrain())];
        for ($i = 0; $i < $x; ++$i) {
            for ($j = 0; $j < $y; ++$j) {
                $this->map[$i][$j] = new Terrain();
            }
        }
    }

    /**
     * @param string     $characteristic
     * @param string     $type
     * @param Dimensions $from
     * @param Dimensions $to
     */
    protected function setTerrainCharacteristic($characteristic, $type, Dimensions $from, Dimensions $to): void
    {
        if ('Quality' === $type || 'Height' === $type) {
            $function = 'set' . $type;
            for ($x = $from->getX(); $x <= $to->getX(); ++$x) {
                for ($y = $from->getY(); $y <= $to->getY(); ++$y) {
                    $this->map[$x][$y]->$function($characteristic);
                }
            }
        }
    }


}