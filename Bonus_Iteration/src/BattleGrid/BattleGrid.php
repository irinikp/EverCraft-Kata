<?php


namespace EverCraft\BattleGrid;


use EverCraft\Character;

/**
 * Class BattleGrid
 * @package EverCraft\BattleGrid
 */
class BattleGrid
{
    /**
     * @var Dimension
     */
    protected $dimensions;
    /**
     * @var Terrain[][]
     */
    protected $map;
    /**
     * @var Character[][]
     */
    protected $character_positions;

    /**
     * BattleGrid constructor.
     */
    public function __construct()
    {
        $this->map                 = [];
        $this->character_positions = [];
    }

    /**
     * @return Dimension
     */
    public function getDimensions(): Dimension
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
        $this->dimensions = new Dimension($x, $y);
        $this->setTerrain($this->dimensions);
        $this->initiatePositions();
    }

    /**
     * @param Dimension $spot
     *
     * @return bool
     */
    public function isSpotEmpty(Dimension $spot): bool
    {
        return null === $this->character_positions[$spot->getX()][$spot->getY()];
    }

    /**
     * @param string    $height
     * @param Dimension $from
     * @param Dimension $to
     */
    public function setTerrainHeight($height, Dimension $from, Dimension $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($height, 'Height', $from, $to);
    }

    /**
     * @param string    $quality
     * @param Dimension $from
     * @param Dimension $to
     */
    public function setTerrainQuality($quality, Dimension $from, Dimension $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($quality, 'Quality', $from, $to);
    }

    /**
     * @param Dimension $dimension
     *
     * @return string
     */
    public function getTerrainHeight(Dimension $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getHeight();
    }

    /**
     * @param Dimension $dimension
     *
     * @return string
     */
    public function getTerrainQuality(Dimension $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getQuality();
    }

    /**
     * @param Character $character
     * @param Dimension $dimension
     */
    public function place(Character $character, Dimension $dimension): void
    {
        $this->character_positions[$dimension->getX()][$dimension->getY()] = $character;
    }

    /**
     * @param Dimension $dimension
     */
    protected function checkBounds(Dimension $dimension): void
    {
        if (sizeof($this->map) <= 0) throw new \OutOfBoundsException('The map has not been initiated');
        if ($dimension->getX() >= sizeof($this->map)) throw new \OutOfBoundsException($dimension->getX() . ' is out of the map');
        if ($dimension->getY() >= sizeof($this->map)) throw new \OutOfBoundsException($dimension->getY() . ' is out of the map');
    }

    /**
     * @param Dimension $dimension
     */
    protected function setTerrain(Dimension $dimension): void
    {
        // I don't use array_fill because it uses one new Terrain object for all instances,
        // whereas i need a different one for each map position
        for ($i = 0; $i < $dimension->getX(); ++$i) {
            for ($j = 0; $j < $dimension->getY(); ++$j) {
                $this->map[$i][$j] = new Terrain();
            }
        }
    }

    /**
     *
     */
    protected function initiatePositions(): void
    {
        $this->character_positions = array_fill(0, $this->dimensions->getX(),
            array_fill(0, $this->dimensions->getY(), null));
    }

    /**
     * @param string    $characteristic
     * @param string    $type
     * @param Dimension $from
     * @param Dimension $to
     */
    protected function setTerrainCharacteristic($characteristic, $type, Dimension $from, Dimension $to): void
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
