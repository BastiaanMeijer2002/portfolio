<?php

namespace App\Controller;

use App\Entity\PortfolioElement;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
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
        $this->repository = $doctrine->getRepository(PortfolioElement::class);
    }

}
