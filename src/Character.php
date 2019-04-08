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
     * Character constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getName()
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
     * @return Alignment
     */
    public function getAlignment()
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

}