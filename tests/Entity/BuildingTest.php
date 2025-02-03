<?php

namespace App\Tests\Entity;

use App\Entity\Building;
use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{
    private Building $building;

    protected function setUp(): void
    {
        $this->building = new Building();
    }

    public function testName(): void
    {
        $name = 'Test Building';
        $this->building->setName($name);
        $this->assertEquals($name, $this->building->getName());
    }

    public function testAddress(): void
    {
        $address = '123 Test Street';
        $this->building->setAddress($address);
        $this->assertEquals($address, $this->building->getAddress());
    }

    public function testCapacity(): void
    {
        $capacity = 10;
        $this->building->setCapacity($capacity);
        $this->assertEquals($capacity, $this->building->getCapacity());
    }

    public function testAddAndRemoveOccupant(): void
    {
        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');

        // Test adding an occupant
        $this->building->addOccupant($person);
        $this->assertCount(1, $this->building->getOccupants());
        $this->assertTrue($this->building->getOccupants()->contains($person));

        // Test removing an occupant
        $this->building->removeOccupant($person);
        $this->assertCount(0, $this->building->getOccupants());
        $this->assertFalse($this->building->getOccupants()->contains($person));
    }
}
