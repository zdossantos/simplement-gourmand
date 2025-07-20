# README – Installation Locale du projet Simplement Gourmand

Bienvenue ! Voici comment installer et lancer **Simplement Gourmand**.

## Prérequis

- **Docker** & **Docker Compose**
- **Bun** (gestionnaire JS ultra-rapide utilisé pour le front)

## Installation Rapide

### 1. Préparation de l’environnement

```bash
cp .env.example .env
```

```bash
composer install
```

### 2. Lancement des conteneurs Docker

```bash
./vendor/bin/sail up -d
```

### 3. Installation des dépendances frontend (avec Bun)

```bash
bun install
```

### 4. Génération de la clé d’application

```bash
./vendor/bin/sail artisan key:generate
```

### 5. Migration + initialisation sécurité API

```bash
./vendor/bin/sail artisan migrate
```

### 6. Compilation des assets avec Bun

```bash
bun run dev
```

### 7. Accès au projet

- **Application** : http://localhost
- **API** : Endpoints disponibles à partir de `/api`

### 8. Lancer les tests

```bash
./vendor/bin/sail pest
```