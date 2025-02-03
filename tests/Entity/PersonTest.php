<?php

namespace App\Tests\Entity;

use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    private Person $person;

    protected function setUp(): void
    {
        $this->person = new Person();
    }

    public function testFirstName(): void
    {
        $firstName = 'John';
        $this->person->setFirstName($firstName);
        $this->assertEquals($firstName, $this->person->getFirstName());
    }

    public function testLastName(): void
    {
        $lastName = 'Doe';
        $this->person->setLastName($lastName);
        $this->assertEquals($lastName, $this->person->getLastName());
    }

    public function testEmail(): void
    {
        $email = 'john.doe@example.com';
        $this->person->setEmail($email);
        $this->assertEquals($email, $this->person->getEmail());
    }

    public function testBirthDate(): void
    {
        $birthDate = new \DateTimeImmutable('1990-01-01');
        $this->person->setBirthDate($birthDate);
        $this->assertEquals($birthDate, $this->person->getBirthDate());
    }
}
