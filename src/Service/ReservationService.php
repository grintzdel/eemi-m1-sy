<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

final readonly class ReservationService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ReservationRepository $reservationRepository,
    ) {
    }

    public function createReservation(User $user, Event $event): Reservation
    {
        if ($this->hasUserAlreadyReserved($user, $event)) {
            throw new \RuntimeException('Vous avez déjà réservé cet événement.');
        }

        if ($event->isFull()) {
            throw new \RuntimeException('Cet événement est complet.');
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setEvent($event);

        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;
    }

    public function hasUserAlreadyReserved(User $user, Event $event): bool
    {
        return $this->reservationRepository->findOneByUserAndEvent($user, $event) !== null;
    }
}
