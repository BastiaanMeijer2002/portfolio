<?php

namespace App\Controller;

use App\Entity\PortfolioElement;
use App\Entity\Tag;
use App\Repository\PortfolioElementRepository;
use Doctrine\DBAL\Exception;
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
    private ObjectRepository $tagRepository;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->repository = $doctrine->getRepository(PortfolioElement::class);
        $this->tagRepository = $doctrine->getRepository(Tag::class);
    }

    #[Route('/add', name: '_add', methods: ['POST'])]
    public function addElement(Request $request): JsonResponse
    {
        $entityManager = $this->doctrine->getManager();
        $payload = json_decode($request->getContent(), true);

        if ($payload == null || $payload['title'] == null || $payload['desc'] == null || $payload['tags'] == null){
            return $this->json('Invalid input.');
        }

        $element = new PortfolioElement();
        $element->setDescription($payload['desc']);
        $element->setTitle($payload['title']);
        $element->setTimestamp(new \DateTime());

        foreach ($payload['tags'] as $tag){
            $temp = $this->tagRepository->findOneBy(['Name'=>$tag]);

            if ($temp){
                $element->addTag($temp);
            } else {
                $newTag = new Tag();
                $newTag->setName($tag);
                $entityManager->persist($newTag);
                $element->addTag($newTag);
            }
        }

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

        if (isset($payload['tags'])) {
            foreach ($payload['tags'] as $tag) {
                $temp = $this->tagRepository->findOneBy(['Name' => $tag]);

                if ($temp) {
                    $element->addTag($temp);
                } else {
                    $newTag = new Tag();
                    $newTag->setName($tag);
                    $entityManager->persist($newTag);
                    $element->addTag($newTag);
                }
            }
            $entityManager->flush();
        }


        return $this->json(["Element updated successfully!"]);

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

    /**
     * @throws Exception
     */
    #[Route('/get-by-tags', name: '_by_tags', methods: ['POST'])]
    public function getElementsByTags(Request $request, PortfolioElementRepository $repo): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);

        if ($payload == null && $payload['tags'] == null){
            return $this->json("Invalid input");
        }

        $data = $repo->findByTags($payload['tags'], 0);

        return $this->json($data);
    }
}
