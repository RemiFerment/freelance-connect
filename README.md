# FreelanceConnect

##  Présentation

**FreelanceConnect** est une plateforme web mettant en relation des clients et des freelances.

Développée dans le cadre d'un **Minimum Viable Product (MVP)**, l'application permet notamment de :

- créer un compte client ou freelance ;
- publier des missions ;
- consulter les missions disponibles ;
- postuler à une mission ;
- administrer les missions ;
- accéder à une API REST sécurisée.

---

#  Technologies utilisées

- PHP 8.4
- Symfony 7
- Doctrine ORM
- Doctrine MongoDB ODM
- MySQL
- MongoDB
- Docker
- Bootstrap 5
- Twig
- Composer

---

#  Prérequis

Avant de lancer le projet, assurez-vous d'avoir installé :

- PHP 8.4 ou supérieur
- Composer
- Docker Desktop
- Symfony CLI
- Git

---

#  Installation

### Cloner le dépôt

```bash
git clone https://github.com/RemiFerment/freelance-connect.git
cd freelance-connect
```

### Installer les dépendances

```bash
composer install
```

---

#  Configuration

Créer un fichier `.env` à la racine du projet.

Configurer la connexion à la base de données :

```dotenv
DATABASE_URL="mysql://utilisateur:mot_de_passe@database:3306/nom_base?serverVersion=8.0&charset=utf8mb4"
```

Remplacer les valeurs par celles correspondant à votre environnement local.

---

#  Bases de données

L'application utilise deux systèmes de stockage :

- **MySQL** pour les données relationnelles de l'application (utilisateurs, missions, candidatures, etc.) via Doctrine ORM.
- **MongoDB** pour la gestion de la messagerie via Doctrine MongoDB ODM.

## MySQL

### Démarrer les conteneurs Docker

```bash
docker compose up -d
```

### Créer la base de données

```bash
php bin/console doctrine:database:create
```

### Exécuter les migrations

```bash
php bin/console doctrine:migrations:migrate
```

## MongoDB



---

#  Données

Aucun jeu de données de démonstration n'est fourni.

Après l'exécution des migrations, la base de données est vide.

Les utilisateurs peuvent créer un compte grâce à la fonctionnalité d'inscription puis accéder aux fonctionnalités de l'application selon leur rôle.

---

#  Lancer le projet

Démarrer le serveur Symfony :

```bash
symfony server:start
```

L'application sera accessible à l'adresse indiquée par Symfony CLI.

---
#  API REST

L'application expose une API REST sécurisée permettant d'accéder aux ressources principales de la plateforme.

Les endpoints disponibles concernent notamment :

- les missions ;
- les clients ;
- les freelances ;
- les candidatures aux missions.

Les routes privées nécessitent une authentification valide et renvoient des réponses au format JSON.

---

#  Tests

Le projet contient une suite de tests automatisés réalisés avec **PHPUnit**.
Les tests utilisent l'environnement Symfony `test` configuré dans le fichier `.env.test`.

Exemple :

```dotenv
APP_ENV=test

DATABASE_URL="mysql://utilisateur:mot_de_passe@database:3306/nom_base_test?serverVersion=8.0&charset=utf8mb4"
```

Pour exécuter les tests :

```bash
php bin/phpunit
```

Les tests permettent de vérifier le bon fonctionnement des principales fonctionnalités de l'application.

 ---

 #  Post Mortem

Le développement de **FreelanceConnect** a été une expérience permettant de mettre en pratique les compétences acquises durant la formation.

Ce projet nous a permis de travailler sur la conception d'une application complète avec Symfony, notamment la gestion des utilisateurs, l'authentification avec différents rôles, la gestion des missions, la création d'une API REST sécurisée ainsi que l'utilisation de MySQL et MongoDB.

L'une des principales difficultés rencontrées a été la gestion du temps disponible pour réaliser l'ensemble des fonctionnalités prévues dans le cahier des charges. Le choix a donc été fait de prioriser les fonctionnalités essentielles au fonctionnement du MVP afin de livrer une application stable et fonctionnelle.

Certaines fonctionnalités n'ont pas pu être finalisées dans le délai imparti, notamment :
- les statistiques côté administrateur ;
- le service de facturation.

Ces fonctionnalités restent des axes d'évolution pour une prochaine version de l'application.

Ce projet a également permis d'améliorer notre organisation de travail en équipe, notamment à travers l'utilisation de Git, la gestion des branches et la répartition des tâches. Il nous a permis de mieux appréhender les contraintes d'un développement en conditions réelles et l'importance de prioriser les fonctionnalités selon les objectifs du projet.

#  Auteurs

Projet réalisé dans le cadre de la formation **Concepteur Développeur d'Applications (CDA)**.
- Léa Brugière
- Rémi Ferment
