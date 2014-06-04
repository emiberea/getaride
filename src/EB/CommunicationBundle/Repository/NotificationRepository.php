<?php

namespace EB\CommunicationBundle\Repository;

use Doctrine\ORM\EntityRepository;
use EB\UserBundle\Entity\User;

/**
 * NotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NotificationRepository extends EntityRepository
{
    public function countUnreadByReceiverUser(User $user)
    {
        $qb = $this->createQueryBuilder('n');
        $qb->select(
            $qb->expr()->count('n.id')
        );
        $qb->where(
            $qb->expr()->andX(
                $qb->expr()->eq('n.receiverUser', ':user'),
                $qb->expr()->eq('n.isRead', ':isRead')
            )
        );
        $qb->setParameters(array(
            'user' => $user,
            'isRead' => false,
        ));

        return $qb->getQuery()->getSingleScalarResult();
    }
}
