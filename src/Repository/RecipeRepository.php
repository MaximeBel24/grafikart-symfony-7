<?php

namespace App\Repository;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Recipe>
 *
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private PaginatorInterface $paginator)
    {
        parent::__construct($registry, Recipe::class);
    }

    public function paginateRecipes(int $page, $limit): PaginationInterface //: Paginator
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('r')->leftJoin('r.category', 'c')->select('r', 'c'),
            $page,
            $limit,
            [
                'distinct' => false,
                'sortFieldAllowList' => ['r.id', 'r.title']
            ]
        );
        // return new Paginator($this
        //     ->createQueryBuilder('r')
        //     ->setFirstResult(($page - 1) * $limit)
        //     ->setMaxResults($limit)
        //     ->getQuery()
        //     ->setHint(Paginator::HINT_ENABLE_DISTINCT,false),
        //     false
        // );
    }

    public function findTotalDuration()
    {
        return $this->create('r')
            ->select('SUM(r.duration) as total')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Recipe[]
     */
    public function findWithDurationLowerThan(int $duration): array
    {
        return $this->createQueryBuilder('r')
            ->select('r', 'c')
            ->where('r.duration < :duration')
            ->orderBy('r.duration', 'ASC')
            ->leftJoin('r.category', 'c')
//             ->andWhere("c.category='dessert'")
            ->setMaxResults(10)
            ->setParameter('duration', $duration)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Recipe[] Returns an array of Recipe objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Recipe
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
