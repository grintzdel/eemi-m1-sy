<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Service\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class EventController extends AbstractController
{
    public function __construct(
        private readonly EventRepository $eventRepository,
        private readonly ReservationService $reservationService,
    ) {
    }

    #[Route('/', name: 'app_home')]
    #[Route('/events', name: 'app_event_list')]
    public function list(): Response
    {
        $events = $this->eventRepository->findUpcomingEvents();

        return $this->render('event/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/event/{id}', name: 'app_event_show', requirements: ['id' => '\d+'])]
    public function show(Event $event): Response
    {
        $user = $this->getUser();
        $hasAlreadyReserved = false;

        if ($user !== null) {
            $hasAlreadyReserved = $this->reservationService->hasUserAlreadyReserved($user, $event);
        }

        return $this->render('event/show.html.twig', [
            'event' => $event,
            'hasAlreadyReserved' => $hasAlreadyReserved,
        ]);
    }

    #[Route('/event/{id}/reserve', name: 'app_event_reserve', methods: ['POST'], requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_USER')]
    public function reserve(Event $event): Response
    {
        try {
            $this->reservationService->createReservation($this->getUser(), $event);
            $this->addFlash('success', 'Votre réservation a bien été enregistrée !');
        } catch (\RuntimeException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('app_event_show', ['id' => $event->getId()]);
    }
}
