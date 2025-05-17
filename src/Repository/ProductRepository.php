<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findByShopId(int $shopId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.shop = :shopId')
            ->setParameter('shopId', $shopId)
            ->getQuery()
            ->getResult();
    }

    public function findByShopWithSearch(Shop $shop, string $searchTerm = ''): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.shop = :shop')
            ->setParameter('shop', $shop)
            ->orderBy('p.name', 'ASC');

        if ($searchTerm) {
            $queryBuilder
                ->andWhere('p.name LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
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

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
