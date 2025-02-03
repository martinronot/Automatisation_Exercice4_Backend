<?php

namespace App\Command;

use App\Entity\Building;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-test-data',
    description: 'Generate test data for the application',
)]
class GenerateTestDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('fr_FR');
        
        // Generate 20 people
        $people = [];
        for ($i = 0; $i < 20; $i++) {
            $person = new Person();
            $person->setFirstName($faker->firstName());
            $person->setLastName($faker->lastName());
            $person->setEmail($faker->email());
            $person->setBirthDate(new \DateTimeImmutable($faker->date()));
            
            $this->entityManager->persist($person);
            $people[] = $person;
            
            $output->writeln(sprintf(
                'Created person: %s %s',
                $person->getFirstName(),
                $person->getLastName()
            ));
        }
        
        // Generate 5 buildings
        for ($i = 0; $i < 5; $i++) {
            $building = new Building();
            $building->setName($faker->company());
            $building->setAddress($faker->address());
            $building->setCapacity($faker->numberBetween(5, 15));
            
            // Add random occupants
            $numOccupants = $faker->numberBetween(1, $building->getCapacity());
            $randomPeople = $faker->randomElements($people, $numOccupants);
            foreach ($randomPeople as $person) {
                $building->addOccupant($person);
            }
            
            $this->entityManager->persist($building);
            
            $output->writeln(sprintf(
                'Created building: %s with %d occupants',
                $building->getName(),
                count($building->getOccupants())
            ));
        }
        
        $this->entityManager->flush();
        
        $output->writeln('Test data generation completed successfully!');
        
        return Command::SUCCESS;
    }
}
