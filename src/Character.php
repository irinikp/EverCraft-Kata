<?php

namespace Dnd;

/**
 * Class Character
 * @package Dnd
 */
class Character
{

    /**
     * @var string
     */
    protected $name;
    /**
     * @var Alignment
     */
    protected $alignment;

    /**
     * @var int
     */
    protected $ac;
    /**
     * @var int
     */
    protected $hp;

    /**
     * Character constructor.
     */
    public function __construct()
    {
        $this->ac = 10;
        $this->hp = 5;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAlignment(): string
    {
        return $this->alignment;
    }

    /**
     * @param string $alignment
     */
    public function setAlignment($alignment)
    {
        try {
            $this->alignment = new Alignment($alignment);
        } catch (\Exception $e) {
            $this->alignment = '';
        }
    }

    /**
     * @return int
     */
    public function getAc(): int
    {
        return $this->ac;
    }

    /**
     * @param int $ac
     */
    public function setAc($ac): void
    {
        $this->ac = $ac;
    }

    /**
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }

    /**
     * @param int $dice
     *
     * @return int
     */
    public function roll($dice)
    {
        return rand(1, $dice);
    }

}