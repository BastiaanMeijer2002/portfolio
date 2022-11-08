<?php

namespace App\Repository;

use App\Entity\PortfolioElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
