<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Reservation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de l'utilisateur admin
        $admin = new User();
        $admin->setEmail('test-admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'test-admin');
        $admin->setPassword($hashedPassword);
        $manager->persist($admin);

        // Création d'utilisateurs normaux
        $users = [];
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();
            $user->setEmail($faker->unique()->email());
            $user->setRoles(['ROLE_USER']);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);
            $manager->persist($user);
            $users[] = $user;
        }

        // Création d'événements
        $events = [];
        for ($i = 0; $i < 15; ++$i) {
            $event = new Event();
            $event->setTitle($faker->sentence(4));
            $event->setDescription($faker->paragraph(5));
            $event->setDate($faker->dateTimeBetween('+1 day', '+6 months'));
            $event->setLocation($faker->city());
            $event->setCapacity($faker->numberBetween(10, 100));
            $manager->persist($event);
            $events[] = $event;
        }

        // Création de quelques réservations
        for ($i = 0; $i < 30; ++$i) {
            $reservation = new Reservation();
            $reservation->setUser($faker->randomElement($users));
            $reservation->setEvent($faker->randomElement($events));
            $reservation->setCreatedAt($faker->dateTimeBetween('-1 month', 'now'));

            // Vérifier qu'un utilisateur ne réserve pas deux fois le même événement
            $isDuplicate = false;
            foreach ($reservation->getUser()->getReservations() as $existingReservation) {
                if ($existingReservation->getEvent() === $reservation->getEvent()) {
                    $isDuplicate = true;
                    break;
                }
            }

            if (!$isDuplicate && !$reservation->getEvent()->isFull()) {
                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }
}
