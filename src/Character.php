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


}