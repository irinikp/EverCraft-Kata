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
        $this->setTerrain($this->dimensions);
    }

    /**
     * @param string     $height
     * @param Dimensions $from
     * @param Dimensions $to
     */
    public function setTerrainHeight($height, Dimensions $from, Dimensions $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($height, 'Height', $from, $to);
    }

    /**
     * @param string     $quality
     * @param Dimensions $from
     * @param Dimensions $to
     */
    public function setTerrainQuality($quality, Dimensions $from, Dimensions $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($quality, 'Quality', $from, $to);
    }

    /**
     * @param Dimensions $dimension
     *
     * @return string
     */
    public function getTerrainHeight(Dimensions $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getHeight();
    }

    /**
     * @param Dimensions $dimension
     *
     * @return string
     */
    public function getTerrainQuality(Dimensions $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getQuality();
    }

    /**
     * @param Dimensions $dimension
     *
     * @return bool
     */
    protected function checkBounds(Dimensions $dimension): void
    {
        if (sizeof($this->map) <= 0) throw new \OutOfBoundsException('The map has not been initiated');
        if ($dimension->getX() > sizeof($this->map)) throw new \OutOfBoundsException($dimension->getX() . ' is out of the map');
        if ($dimension->getY() > sizeof($this->map)) throw new \OutOfBoundsException($dimension->getY() . ' is out of the map');
    }

    /**
     * @param Dimensions $dimension
     */
    protected function setTerrain(Dimensions $dimension): void
    {
        $this->map = [
            array_fill(0, $dimension->getX(), new Terrain()),
            array_fill(0, $dimension->getY(), new Terrain())
        ];
        for ($x = 0; $x < $dimension->getX(); ++$x) {
            for ($y = 0; $y < $dimension->getY(); ++$y) {
                $this->map[$x][$y] = new Terrain();
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
