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
     * @var CartesianPoint
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
     * @return CartesianPoint
     */
    public function getDimensions(): CartesianPoint
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
        $this->dimensions = new CartesianPoint($x, $y);
        $this->setTerrain($this->dimensions);
        $this->initiatePositions();
    }

    /**
     * @param CartesianPoint $spot
     *
     * @return bool
     */
    public function isSpotEmpty(CartesianPoint $spot): bool
    {
        return null === $this->character_positions[$spot->getX()][$spot->getY()];
    }

    /**
     * @param string         $height
     * @param CartesianPoint $from
     * @param CartesianPoint $to
     */
    public function setTerrainHeight($height, CartesianPoint $from, CartesianPoint $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($height, 'Height', $from, $to);
    }

    /**
     * @param string         $quality
     * @param CartesianPoint $from
     * @param CartesianPoint $to
     */
    public function setTerrainQuality($quality, CartesianPoint $from, CartesianPoint $to): void
    {
        $this->checkBounds($from);
        $this->checkBounds($to);
        $this->setTerrainCharacteristic($quality, 'Quality', $from, $to);
    }

    /**
     * @param CartesianPoint $dimension
     *
     * @return string
     */
    public function getTerrainHeight(CartesianPoint $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getHeight();
    }

    /**
     * @param CartesianPoint $dimension
     *
     * @return string
     */
    public function getTerrainQuality(CartesianPoint $dimension): string
    {
        $this->checkBounds($dimension);
        return $this->map[$dimension->getX()][$dimension->getY()]->getQuality();
    }

    /**
     * @param Character      $character
     * @param CartesianPoint $dimension
     */
    public function place(Character $character, CartesianPoint $dimension): void
    {
        $this->character_positions[$dimension->getX()][$dimension->getY()] = $character;
        $character->setMapPosition($dimension);
    }

    /**
     * @param Character             $character
     * @param array<CartesianPoint> $route
     *
     * @return bool
     */
    public function isRouteTraversable(Character $character, $route): bool
    {
        $current_position = $character->getMapPosition();
        $total_squares    = 0;
        foreach ($route as $spot) {
            $line_squares = $this->getTraversableSquaresNumber($current_position, $spot);
            if ($line_squares < 0) return false;
            $total_squares    += $line_squares;
            $current_position = $spot;
        }
        return ($total_squares <= $character->getMovementSpeed());
    }

    /**
     * @param Character $attacker
     * @param Character $target
     *
     * @return bool
     */
    public function canHit(Character $attacker, Character $target)
    {
        if (!$attacker->holdsWeapon() || !$attacker->getWeapon()->isMissile()) {
            return $this->areCharactersAdjacent($attacker, $target);
        }
        return false;
    }

    /**
     * Characters are adjacent even diagonally
     *
     * @param Character $player1
     * @param Character $player2
     *
     * @return bool
     */
    protected function areCharactersAdjacent(Character $player1, Character $player2): bool
    {
        return CartesianPoint::arePointsAdjacent($player1->getMapPosition(), $player2->getMapPosition());
    }

    /**
     * @param CartesianPoint $starting_point
     * @param CartesianPoint $end_point
     *
     * @return int number of squares, or -1 if an obstacle was found
     */
    protected function getTraversableSquaresNumber(CartesianPoint $starting_point, CartesianPoint $end_point): int
    {
        if ($this->isMovementOnXAxis($starting_point, $end_point)) {
            $squares = $this->getTraversableAxisLineLength($starting_point, $end_point, CartesianPoint::X_AXIS);
        } else {
            $squares = $this->getTraversableAxisLineLength($starting_point, $end_point, CartesianPoint::Y_AXIS);
        }
        return $squares;
    }

    /**
     * @param CartesianPoint $starting_point
     * @param CartesianPoint $end_point
     *
     * @return bool
     */
    protected function isMovementOnXAxis(CartesianPoint $starting_point, CartesianPoint $end_point): bool
    {
        return ($starting_point->getX() !== $end_point->getX());
    }

    /**
     * @param CartesianPoint $starting_point
     * @param CartesianPoint $end_point
     * @param string         $moving_axis the axis parallel to the line
     *
     * @return int number of squares, or -1 if an obstacle was found
     */
    protected function getTraversableAxisLineLength(CartesianPoint $starting_point, CartesianPoint $end_point, $moving_axis)
    {
        $squares               = $this->getPointTerrainSpeed($starting_point);
        $fixed_axis            = CartesianPoint::getVerticalAxis($moving_axis);
        $get_moving_coordinate = "get$moving_axis";
        $get_fixed_coordinate  = "get$fixed_axis";
        $starting_coordinate   = $starting_point->$get_moving_coordinate();
        $ending_coordinate     = $end_point->$get_moving_coordinate();
        if ($starting_coordinate > $ending_coordinate) {
            $starting_coordinate = $end_point->$get_moving_coordinate();
            $ending_coordinate   = $starting_point->$get_moving_coordinate();
        }
        for ($i = $starting_coordinate + 1; $i < $ending_coordinate; ++$i) {
            $current_point = new CartesianPoint($i, $starting_point->$get_fixed_coordinate());
            if (CartesianPoint::Y_AXIS === $moving_axis) $current_point = new CartesianPoint($starting_point->$get_fixed_coordinate(), $i);
            if (!$this->isSpotEmpty($current_point)) {
                return -1;
            }
            $squares += $this->getPointTerrainSpeed($current_point);
        }
        return $squares;
    }

    /**
     * @param CartesianPoint $point
     *
     * @return int
     */
    protected function getPointTerrainSpeed(CartesianPoint $point): int
    {
        $speed = 1;
        if (Terrain::DIFFICULT === $this->map[$point->getX()][$point->getY()]->getQuality()) {
            $speed++;
        }
        return $speed;
    }


    /**
     * @param CartesianPoint $dimension
     */
    protected function checkBounds(CartesianPoint $dimension): void
    {
        if (sizeof($this->map) <= 0) throw new \OutOfBoundsException('The map has not been initiated');
        if ($dimension->getX() >= sizeof($this->map)) throw new \OutOfBoundsException($dimension->getX() . ' is out of the map');
        if ($dimension->getY() >= sizeof($this->map)) throw new \OutOfBoundsException($dimension->getY() . ' is out of the map');
    }

    /**
     * @param CartesianPoint $dimension
     */
    protected function setTerrain(CartesianPoint $dimension): void
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
     * @param string         $characteristic
     * @param string         $type
     * @param CartesianPoint $from
     * @param CartesianPoint $to
     */
    protected function setTerrainCharacteristic($characteristic, $type, CartesianPoint $from, CartesianPoint $to): void
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
