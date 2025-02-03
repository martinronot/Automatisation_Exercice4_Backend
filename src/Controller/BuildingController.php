<?php

namespace App\Controller;

use App\Entity\Building;
use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/building', name: 'api_building_')]
class BuildingController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): JsonResponse
    {
        $buildings = $entityManager->getRepository(Building::class)->findAll();
        $data = [];
        
        foreach ($buildings as $building) {
            $occupants = [];
            foreach ($building->getOccupants() as $occupant) {
                $occupants[] = [
                    'id' => $occupant->getId(),
                    'firstName' => $occupant->getFirstName(),
                    'lastName' => $occupant->getLastName()
                ];
            }
            
            $data[] = [
                'id' => $building->getId(),
                'name' => $building->getName(),
                'address' => $building->getAddress(),
                'capacity' => $building->getCapacity(),
                'occupants' => $occupants
            ];
        }

        return $this->json($data);
    }

    #[Route('/', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $building = new Building();
        $building->setName($data['name']);
        $building->setAddress($data['address']);
        $building->setCapacity($data['capacity']);

        $entityManager->persist($building);
        $entityManager->flush();

        return $this->json([
            'message' => 'Building created successfully',
            'id' => $building->getId()
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Building $building): JsonResponse
    {
        $occupants = [];
        foreach ($building->getOccupants() as $occupant) {
            $occupants[] = [
                'id' => $occupant->getId(),
                'firstName' => $occupant->getFirstName(),
                'lastName' => $occupant->getLastName()
            ];
        }

        return $this->json([
            'id' => $building->getId(),
            'name' => $building->getName(),
            'address' => $building->getAddress(),
            'capacity' => $building->getCapacity(),
            'occupants' => $occupants
        ]);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Building $building, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $building->setName($data['name']);
        }
        if (isset($data['address'])) {
            $building->setAddress($data['address']);
        }
        if (isset($data['capacity'])) {
            $building->setCapacity($data['capacity']);
        }

        $entityManager->flush();

        return $this->json([
            'message' => 'Building updated successfully'
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Building $building, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($building);
        $entityManager->flush();

        return $this->json([
            'message' => 'Building deleted successfully'
        ]);
    }

    #[Route('/{id}/occupants', name: 'add_occupant', methods: ['POST'])]
    public function addOccupant(Request $request, Building $building, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $personId = $data['personId'];

        $person = $entityManager->getRepository(Person::class)->find($personId);
        if (!$person) {
            return $this->json(['message' => 'Person not found'], 404);
        }

        if (count($building->getOccupants()) >= $building->getCapacity()) {
            return $this->json(['message' => 'Building is at full capacity'], 400);
        }

        $building->addOccupant($person);
        $entityManager->flush();

        return $this->json([
            'message' => 'Occupant added successfully'
        ]);
    }

    #[Route('/{id}/occupants/{personId}', name: 'remove_occupant', methods: ['DELETE'])]
    public function removeOccupant(Building $building, int $personId, EntityManagerInterface $entityManager): JsonResponse
    {
        $person = $entityManager->getRepository(Person::class)->find($personId);
        if (!$person) {
            return $this->json(['message' => 'Person not found'], 404);
        }

        $building->removeOccupant($person);
        $entityManager->flush();

        return $this->json([
            'message' => 'Occupant removed successfully'
        ]);
    }
}
