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
     * @return Terrain[][]
     */
    public function getMap(): array
    {
        return $this->map;
    }

    /**
     * @param Terrain[][] $map
     */
    public function setMap(array $map): void
    {
        $this->map = $map;
    }

    /**
     * @return Character[][]
     */
    public function getCharacterPositions(): array
    {
        return $this->character_positions;
    }

    /**
     * @param Character[][] $character_positions
     */
    public function setCharacterPositions(array $character_positions): void
    {
        $this->character_positions = $character_positions;
    }

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
        $character->setMapPosition($dimension);
    }

    /**
     * @param Character        $character
     * @param array<Dimension> $route
     *
     * @return bool
     */
    public function isRouteObstacleFree(Character $character, $route): bool
    {
        $current_position = $character->getMapPosition();
        foreach ($route as $spot) {
            if (!$this->isLineObstacleFree($current_position, $spot)) return false;
            $current_position = $spot;
        }
        return true;
    }

    /**
     * @param Dimension $starting_point
     * @param Dimension $end_point
     *
     * @return bool
     */
    protected function isLineObstacleFree(Dimension $starting_point, Dimension $end_point): bool
    {
        if ($this->isMovementOnXAxis($starting_point, $end_point)) {
            if (!$this->isAxisLineObstacleFree($starting_point, $end_point, Dimension::X_AXIS)) {
                return false;
            }
        } else {
            if (!$this->isAxisLineObstacleFree($starting_point, $end_point, Dimension::Y_AXIS)) {
                return false;
            }
        }
        return true;
    }

    protected function isMovementOnXAxis(Dimension $starting_point, Dimension $end_point): bool
    {
        return ($starting_point->getX() !== $end_point->getX());
    }

    /**
     * @param Dimension $starting_point
     * @param Dimension $end_point
     * @param string    $moving_axis the axis parallel to the line
     *
     * @return bool
     */
    protected function isAxisLineObstacleFree(Dimension $starting_point, Dimension $end_point, $moving_axis)
    {
        $fixed_axis            = Dimension::getVerticalAxis($moving_axis);
        $get_moving_coordinate = "get$moving_axis";
        $get_fixed_coordinate  = "get$fixed_axis";
        $starting_coordinate   = $starting_point->$get_moving_coordinate();
        $ending_coordinate     = $end_point->$get_moving_coordinate();
        if ($starting_coordinate > $ending_coordinate) {
            $starting_coordinate = $end_point->$get_moving_coordinate();
            $ending_coordinate   = $starting_point->$get_moving_coordinate();
        }
        for ($i = $starting_coordinate + 1; $i < $ending_coordinate; ++$i) {
            $current_point = new Dimension($i, $starting_point->$get_fixed_coordinate());
            if (Dimension::Y_AXIS === $moving_axis) $current_point = new Dimension($starting_point->$get_fixed_coordinate(), $i);
            if (!$this->isSpotEmpty($current_point)) {
                return false;
            }
        }
        return true;
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
