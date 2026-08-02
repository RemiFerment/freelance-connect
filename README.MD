# FreelanceConnect

## Présentation

**FreelanceConnect** est une plateforme web mettant en relation des clients et des freelances.

Développée dans le cadre d'un **Minimum Viable Product (MVP)**, l'application permet notamment de :

- créer un compte client ou freelance ;
- publier des missions ;
- consulter les missions disponibles ;
- postuler à une mission ;
- administrer les missions ;
- accéder à une API REST sécurisée.

---

# Technologies utilisées

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

# Prérequis

Avant de lancer le projet, assurez-vous d'avoir installé :

- PHP 8.4 ou supérieur
- Composer
- Docker Desktop
- Symfony CLI
- Git

---

# Installation

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

# Configuration

Créer un fichier `.env` à la racine du projet.

Configurer la connexion à la base de données :

```dotenv
DATABASE_URL="mysql://utilisateur:mot_de_passe@database:3306/nom_base?serverVersion=8.0&charset=utf8mb4"
```

Remplacer les valeurs par celles correspondant à votre environnement local.

---

# Bases de données

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

### Démarrer MongoDB

MongoDB est configuré via Docker Compose. Les conteneurs sont lancés avec la commande :

```bash
docker compose up -d
```

### Initialiser la connexion

La connexion à MongoDB est configurée dans le fichier `.env` :

```dotenv
MONGODB_URL="mongodb://mongo:27017"
MONGODB_DB="freelance_connect"
```

Aucune migration n'est requise pour MongoDB. Doctrine MongoDB ODM crée automatiquement les collections lors de la première utilisation.

---

# Données

Aucun jeu de données de démonstration n'est fourni.

Après l'exécution des migrations, la base de données est vide.

Les utilisateurs peuvent créer un compte grâce à la fonctionnalité d'inscription puis accéder aux fonctionnalités de l'application selon leur rôle.

---

# Lancer le projet

Démarrer le serveur Symfony :

```bash
symfony server:start
```

L'application sera accessible à l'adresse indiquée par Symfony CLI.

---

# API REST

L'application expose une API REST sécurisée permettant d'accéder aux ressources principales de la plateforme.

Les endpoints disponibles concernent notamment :

- les missions ;
- les clients ;
- les freelances ;
- les candidatures aux missions.

Les routes privées nécessitent une authentification valide et renvoient des réponses au format JSON.

---

# Tests

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

# Auteurs

Projet réalisé dans le cadre de la formation **Concepteur Développeur d'Applications (CDA)**.

- Léa Brugière
- Rémi Ferment
