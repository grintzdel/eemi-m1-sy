# Projet Gestion d'Événements - Symfony

## Démarrage du projet

### 1. Lancer Docker
```bash
# Démarrer le projet
docker compose up -d

# Ou rebuilder les images si nécessaire
docker compose build --pull --no-cache
docker compose up -d
```

### 2. Créer la base de données et charger les fixtures
```bash
# Créer la base de données
docker compose exec php bin/console doctrine:database:create

# Exécuter les migrations
docker compose exec php bin/console doctrine:migrations:migrate --no-interaction

# Charger les fixtures (données de test)
docker compose exec php bin/console doctrine:fixtures:load --no-interaction
```

### 3. Arrêter le projet
```bash
docker compose down
```

## Accès à l'application

- **Application web** : http://localhost ou https://localhost

## Identifiants de test

### Compte Administrateur
- **Email** : `test-admin@example.com`
- **Mot de passe** : `test-admin`
- **Rôle** : ROLE_ADMIN

### Comptes utilisateurs
- 10 utilisateurs générés automatiquement par les fixtures
- **Mot de passe** : `password` (pour tous)
- **Rôle** : ROLE_USER

## Adminer (Interface de gestion de base de données)

- **URL** : http://localhost:8080

### Identifiants de connexion Adminer
- **Système** : PostgreSQL
- **Serveur** : `database`
- **Utilisateur** : `app`
- **Mot de passe** : `!ChangeMe!`
- **Base de données** : `app`

## Configuration de la base de données

- **Type** : PostgreSQL 16
- **Host** : `localhost`
- **Port** : `5432`
- **Database** : `app`
- **Username** : `app`
- **Password** : `!ChangeMe!`

## Commandes utiles

```bash
# Voir les logs
docker compose logs -f

# Accéder au container PHP
docker compose exec php bash

# Vider le cache
docker compose exec php bin/console cache:clear

# Créer une nouvelle migration
docker compose exec php bin/console make:migration

# Voir les routes
docker compose exec php bin/console debug:router
```
