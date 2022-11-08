<?php

namespace App\Controller;

use App\Entity\PortfolioElement;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


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

    #[Route('/add', name: '_add')]
    public function addElement(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
//        $payload = json_decode($request->getContent());
        $element = new PortfolioElement();
        $element->setDescription('This is a test element!');
        $element->setTitle('Test');
        $element->setTimestamp(new \DateTime());

        $entityManager->persist($element);
        $entityManager->flush();

        return $this->json(['Element added successfully!']);
    }

    #[Route('/get-all', name: '_get-all')]
    public function index(): JsonResponse
    {
        return $this->json($this->repository->findAll());
    }
}
