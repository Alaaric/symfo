## Ce projet est composé de trois applications Symfony distinctes :

- **le client**
- **l’admin**
- **l’API**

---

## Prérequis

- Avoir PHP 8.3 et Composer installés.
- Avoir la CLI Symfony installée
- Avoir un serveur SMTP (celui de gmail par exemple)

---

## Installation

1. Assurez-vous d’être dans le répertoire racine du projet, puis installez les dépendances :

   ```bash
   cd client
   composer install

   cd ../admin
   composer install

   cd ../api
   composer install

   cd ..
   ```

---

2. Créer les .env.local et les compléter

   Le plus important est le .env de l'api avec notamment:

   - `DATABASE_URL`
   - `EMAIL`
   - `MAILER_DSN`

---

3. Créer la base de données avec les fixtures de test

Depuis le dossier `api` :

```bash
php bin/console d:d:c

php bin/console d:m:m

php bin/console d:f:l
```

---

4. Utilisation des commandes Make
   Le Makefile propose des commandes pour lancer ou arrêter chaque application individuellement, ainsi qu’une commande générale pour tout lancer/arrêter.

- Lancer toutes les applications (client, admin et API) :

  - `make start-all`

- Arrêter tous les services :

  - `make stop-all`

- Lancer le client (port 8000) :

  - `make start-client`

- Lancer l’admin (port 8001) :

  - `make start-admin`

- Lancer l’API (port 8002) :

  - `make start-api`

- Arrêter un service en particulier :

  - `make stop-client`

  - `make stop-admin`

  - `make stop-api`

---

5. Pour vérifier que vos projets sont bien démarrés, vous pouvez vous rendre sur :

- http://127.0.0.1:8000 pour le client
- http://127.0.0.1:8001 pour l’admin
- http://127.0.0.1:8002 pour l’API

5. Démarrer les Workers **dans le dossier api**:

```bash
php bin/console messenger:consume async

php bin/console messenger:consume scheduler_email
```

---

## Remarques

- Les commandes sont adaptées à un environnement Windows avec Git Bash, vous pouvez adapter les commandes selon votre environnement ou lancer manuellement chaque symfony sur les bon port.
- Le projet n’ayant pas pour but d’être en production et est plus un POC qu'une réel application, par simplicité :
  - Le SSL est désactivé.
  - Aucune vérification de données n’est faite (back comme front).
  - Aucune protection n’est en place.
  - Les images sont enregistrés dans un dossier public et donc accessible sans controle
  - L'architecture est loin d'être correct
