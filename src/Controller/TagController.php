<?php

namespace App\Controller;

use App\Entity\PortfolioElement;
use App\Entity\Tag;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/tag', name: 'app_tag')]
class TagController extends AbstractController
{
    private ObjectRepository $repository;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(Tag::class);
    }

    #[Route('/add', name: '_add', methods: ['POST'])]
    public function addElement(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $payload = json_decode($request->getContent(), true);

        if ($payload == null || $payload['name'] == null || $payload['desc'] == null){
            return $this->json('Invalid input.');
        }

        $element = new Tag();
        $element->setName($payload['name']);
        $element->setDescription($payload['desc']);

        $entityManager->persist($element);
        $entityManager->flush();

        return $this->json(['Element added successfully!']);

    }

    #[Route('/delete/{id}', name: '_delete')]
    public function removeElement($id): JsonResponse
    {
        $element = $this->repository->find($id);

        if ($element == null){
            return $this->json("Desired element was not found!");
        }

        $this->repository->remove($element, true);

        return $this->json('Element '.$element->getTitle().' successfully removed!');

    }

    #[Route('/update', name: '_update', methods: ['POST'])]
    public function updateElement(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $payload = json_decode($request->getContent(), true);

        if ($payload == null) {
            return $this->json("There is nothing to update!");
        }

        $element = $this->repository->find($payload['id']);

        if ($element == null) {
            return $this->json("Desired element is not found!");
        }

        if (isset($payload['title'])) {
            $element->setTitle($payload['title']);
            $entityManager->flush();
        }

        if (isset($payload['desc'])) {
            $element->setDescription($payload['desc']);
            $entityManager->flush();
        }

        return $this->json(["Element updated successfully!"]);

    }

}
