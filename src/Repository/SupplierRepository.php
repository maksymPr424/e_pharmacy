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
            ->setParameter('shop', $shop->getId())
            ->orderBy('s.name', 'ASC');

        if (isset($filters['name']) && trim($filters['name']) !== '') {
            $queryBuilder
                ->andWhere('LOWER(s.name) LIKE :name')
                ->setParameter('name', '%' . strtolower(trim($filters['name'])) . '%');
        }

        if (isset($filters['address']) && trim($filters['address']) !== '') {
            $queryBuilder
                ->andWhere('LOWER(s.address) LIKE :address')
                ->setParameter('address', '%' . strtolower(trim($filters['address'])) . '%');
        }

        if (isset($filters['company']) && trim($filters['company']) !== '') {
            $queryBuilder
                ->andWhere('LOWER(s.company) LIKE :company')
                ->setParameter('company', '%' . strtolower(trim($filters['company'])) . '%');
        }

        if (isset($filters['status']) && trim($filters['status']) !== '') {
            $queryBuilder
                ->andWhere('s.status = :status')
                ->setParameter('status', trim($filters['status']));
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
