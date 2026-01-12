<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Event>
 */
final class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * Retourne les événements à venir triés par date croissante.
     *
     * @return Event[]
     */
    public function findUpcomingEvents(): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.date > :now')
            ->setParameter('now', new \DateTime())
            ->orderBy('e.date', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
