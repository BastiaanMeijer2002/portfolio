<?php

namespace App\Controller;

use App\Entity\PortfolioElement;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Flex\Response;


#[Route('/portfolio', name: 'app_portfolio')]
class PortfolioController extends AbstractController
{

    private ObjectRepository $repository;
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(PortfolioElement::class);
    }

    #[Route('/add', name: '_add', methods: ['POST'])]
    public function addElement(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $payload = json_decode($request->getContent(), true);

        if ($payload == null || $payload['title'] == null || $payload['desc'] == null){
            return $this->json('Invalid input.');
        }

        $element = new PortfolioElement();
        $element->setDescription($payload['desc']);
        $element->setTitle($payload['title']);
        $element->setTimestamp(new \DateTime());

        $entityManager->persist($element);
        $entityManager->flush();

        return $this->json(['Element added successfully!']);


    }

    #[Route('/get-all', name: '_get-all')]
    public function getAll(ManagerRegistry $doctrine): JsonResponse
    {
        $repository = $doctrine->getRepository(PortfolioElement::class);
        $data = [];
        foreach ($repository->findAll() as $item){
            $data[] = [$item->getTitle(), $item->getDescription(), $item->getTimestamp()];
        }
        return $this->json($data);
    }
}
