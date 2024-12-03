Voici le texte formaté pour un README GitHub :

---

# Alhambra Project

Alhambra est une application Symfony destinée à la gestion des commissions, des utilisateurs et des notifications dans un cadre associatif.

## Prérequis

Avant d'installer le projet, assurez-vous que votre environnement respecte les prérequis suivants :

- **PHP** : Version 8.1 ou supérieure
- **Composer** : Gestionnaire de dépendances PHP
- **Symfony CLI** : Outil pour gérer les projets Symfony
- **Node.js** : Version 16 ou supérieure, avec **npm** (gestionnaire de packages Node)
- **MySQL** : Base de données relationnelle
- **Git** : Pour cloner le projet

## Installation

### Étape 1 : Cloner le dépôt

Clonez le projet sur votre poste :

```bash
git clone https://github.com/AdrienCarles/alhambra.git
cd alhambra
```

### Étape 2 : Installer les dépendances PHP

Installez les dépendances PHP nécessaires :

```bash
composer install
```

### Étape 3 : Installer les dépendances Node.js

Installez les dépendances front-end :

```bash
npm install
```

### Étape 4 : Configurer l'environnement

1. Créez une copie du fichier `.env` :

   ```bash
   cp .env .env.local
   ```

2. Modifiez le fichier `.env.local` pour configurer les variables d'environnement :

   - **Exemple de configuration de la base de données :**

     ```ini
     DATABASE_URL="mysql://username:password@127.0.0.1:3306/alhambra?serverVersion=8.0"
     ```

   - **Configuration de l'envoi des mails (facultatif) :**

     ```ini
     MAILER_DSN="smtp://localhost"
     ```

### Étape 5 : Configurer la base de données

1. Créez la base de données :

   ```bash
   php bin/console doctrine:database:create
   ```

2. Appliquez les migrations pour générer les tables :

   ```bash
   php bin/console doctrine:migrations:migrate
   ```

3. *(Facultatif)* Ajoutez des données de test :

   ```bash
   php bin/console doctrine:fixtures:load
   ```

### Étape 6 : Compiler les assets front-end

Utilisez Webpack Encore pour compiler les fichiers front-end :

- En mode développement :

  ```bash
  npm run dev
  ```

- En mode production :

  ```bash
  npm run build
  ```

### Étape 7 : Lancer le serveur

Démarrez le serveur Symfony :
```bash
symfony serve
```

L'application sera accessible à l'adresse suivante : [http://127.0.0.1:8000](http://127.0.0.1:8000)
