<?php

namespace App\Repository;

use App\Entity\PortfolioElement;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PortfolioElement>
 *
 * @method PortfolioElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method PortfolioElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method PortfolioElement[]    findAll()
 * @method PortfolioElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortfolioElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PortfolioElement::class);
    }

    public function save(PortfolioElement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PortfolioElement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllElements(): array
    {
        return $this->findAll();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByTags(Array $tags, int $accuracy): array
    {
        $tagRepo = $this->getEntityManager()->getRepository(Tag::class);
        $conn = $this->getEntityManager()->getConnection();

        if ($accuracy == 1){
            $operator = 'and ';
        } else {
            $operator = 'or ';
        }

        $query = '
            select distinct p.id, p.title, p.description, p.timestamp 
            from portfolio_element p
            join portfolio_element_tag pet on p.id = pet.portfolio_element_id
            join tag t on t.id = pet.tag_id
            ';

        for ($i = 0; $i<count($tags); $i++)  {
            $tag = $tagRepo->findOneBy(['Name' => $tags[$i]]);

            if ($tag == null) {
                continue;
            }

            if ($i == 0){
                $query .= 'where t.name = "'. $tag->getName().'"';
            } else {
                $query .= ' '.$operator.' t.name = "'. $tag->getName().'"';
            }
        }

        $stmt = $conn->prepare($query);
        $results = $stmt->executeQuery();

        return $results->fetchAllAssociative();
    }
//    /**
//     * @return PortfolioElement[] Returns an array of PortfolioElement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PortfolioElement
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
