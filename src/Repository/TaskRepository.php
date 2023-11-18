<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findForPagination(?User $user = null): Query
    {
        $qb = $this->createQueryBuilder('t')
        ->orderBy('t.id', 'DESC');

        if ($user) {
            $qb->leftJoin('t.user', 'u')
                ->where($qb->expr()->eq('u.id', ':userId'))
                ->setParameter('userId', $user->getId());
        }
        return $qb->getQuery();
    }

    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findTaskWithUser(string $taskId): ?Task
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u') // Select trick, user
            ->leftJoin('t.user', 'u') // Join with User entity
            ->where('t.id = :id')
            ->setParameter('id', $taskId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findTaskWithFinished(?bool $taskIsDone = true): Query
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'u') // Select trick, user
            ->leftJoin('t.user', 'u') // Join with User entity
            ->where('t.isDone = :isDone')
            ->setParameter('isDone', $taskIsDone)
            ->getQuery();
    }
//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
