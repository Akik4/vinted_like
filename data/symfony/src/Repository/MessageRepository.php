<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    //    /**
    //     * @return Message[] Returns an array of Message objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Message
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findMessages($convId): ?Query
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.conversation_id = :val')
            ->setParameter('val', $convId)
            ->getQuery()
        ;
    }

    public function findConversationInfo($convId): ?Query
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.conversation_id = :val')
            ->setParameter('val', $convId)
            ->orderBy('f.datetime', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
        ;
    }

    public function findConversations($userid): ?Query
    {
        return $this->createQueryBuilder('f')
            ->select('f.conversation_id')
            ->Where('f.User = :val')
            ->orWhere('f.user2 = :val')
            ->join(User::class, 'u')
            ->setParameter('val', $userid)
            ->distinct()
            ->setMaxResults(10)
            ->getQuery()
            // TODO sort by recent date
        ;
    }
}
