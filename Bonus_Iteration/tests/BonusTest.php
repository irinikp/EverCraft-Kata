<?php

namespace Tests;

require __DIR__ . '/../vendor/autoload.php';

use EverCraft\Character;

class BonusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Character
     */
    protected $character;

    public function setUp(): void
    {
        parent::setUp();
        $this->character = new Character();
        $this->character->setName('Bilbur');
    }
}