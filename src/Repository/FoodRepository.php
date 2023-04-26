<?php

namespace App\Repository;

use App\Entity\Food;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<Food>
 *
 * @method Food|null find($id, $lockMode = null, $lockVersion = null)
 * @method Food|null findOneBy(array $criteria, array $orderBy = null)
 * @method Food[]    findAll()
 * @method Food[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Food::class);
    }

    public function save(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Food $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllFilteredByQueryParameters(Request $request): Query
    {
        $query = $this->createQueryBuilder('f');

        if ($category = $request->query->get('category')) {
            if ($category === 'NULL') {
                $query->andWhere('f.category IS NULL');
            } else if ($category === '!NULL') {
                $query->andWhere('f.category IS NOT NULL');
            } else {
                $query->andWhere('f.category = :category')->setParameter('category', $category);
            }
        }

        if ($tags = $request->query->get('tags')) {
            $tags = explode(',', $tags);
            $query->innerJoin('f.tags', 'food_tags')
                ->andWhere('food_tags.id IN (:tags)')
                ->setParameter('tags', $tags);

            foreach ($tags as $tag) {
                $query->andWhere(':tagId{$tag} MEMBER OF f.tags')
                    ->setParameter('tagId{$tag}', $tag);
            }
        }

        if ($diffTime = $request->query->get('diff_time')) {
            $date = date('Y-m-d H:i:s', $diffTime);

            $query->andWhere('f.createdAt <= :diff_time')
                ->orWhere('f.updatedAt <= :diff_time')
                ->orWhere('f.deletedAt <= :diff_time')
                ->setParameter('diff_time', $date);
        } else {
            $query->andWhere('f.deletedAt IS NULL');
        }

        return $query->getQuery();
    }

//    /**
//     * @return Food[] Returns an array of Food objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Food
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
