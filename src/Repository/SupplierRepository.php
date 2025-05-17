<?php

namespace App\Repository;

use App\Entity\Shop;
use App\Entity\Supplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Supplier>
 */
class SupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supplier::class);
    }

    public function findByShop(Shop $shop): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.shop = :shop')
            ->setParameter('shop', $shop)
            ->orderBy('s.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByShopWithFilters(Shop $shop, array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('s')
            ->andWhere('s.shop = :shop')
            ->setParameter('shop', $shop)
            ->orderBy('s.name', 'ASC');

        // Apply filters
        if (!empty($filters['name'])) {
            $queryBuilder
                ->andWhere('LOWER(s.name) LIKE LOWER(:name)')
                ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['address'])) {
            $queryBuilder
                ->andWhere('LOWER(s.address) LIKE LOWER(:address)')
                ->setParameter('address', '%' . $filters['address'] . '%');
        }

        if (!empty($filters['company'])) {
            $queryBuilder
                ->andWhere('LOWER(s.company) LIKE LOWER(:company)')
                ->setParameter('company', '%' . $filters['company'] . '%');
        }

        if (!empty($filters['status'])) {
            $queryBuilder
                ->andWhere('s.status = :status')
                ->setParameter('status', $filters['status']);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Supplier[] Returns an array of Supplier objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Supplier
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
