<?php

namespace App\Controller;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/person')]
class PersonController extends AbstractController
{
    #[Route('/', name: 'app_person_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $people = $entityManager->getRepository(Person::class)->findAll();
        return $this->render('person/index.html.twig', [
            'people' => $people,
        ]);
    }

    #[Route('/new', name: 'app_person_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $person = new Person();
            $person->setFirstName($request->request->get('firstName'));
            $person->setLastName($request->request->get('lastName'));
            $person->setEmail($request->request->get('email'));
            $person->setBirthDate(new \DateTimeImmutable($request->request->get('birthDate')));

            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('app_person_index');
        }

        return $this->render('person/new.html.twig');
    }

    #[Route('/{id}', name: 'app_person_show', methods: ['GET'])]
    public function show(Person $person): Response
    {
        return $this->render('person/show.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_person_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $person->setFirstName($request->request->get('firstName'));
            $person->setLastName($request->request->get('lastName'));
            $person->setEmail($request->request->get('email'));
            $person->setBirthDate(new \DateTimeImmutable($request->request->get('birthDate')));

            $entityManager->flush();

            return $this->redirectToRoute('app_person_index');
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
        ]);
    }

    #[Route('/{id}', name: 'app_person_delete', methods: ['POST'])]
    public function delete(Request $request, Person $person, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$person->getId(), $request->request->get('_token'))) {
            $entityManager->remove($person);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_person_index');
    }
}
