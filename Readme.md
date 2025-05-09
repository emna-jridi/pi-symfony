# 📊 NextGenHR — Gestion RH Moderne avec Symfony

NextGenHR est une application web basée sur le framework **Symfony** dédiée à la **gestion des ressources humaines**. Ce projet propose une solution centralisée, intuitive et modulaire pour répondre aux besoins RH d’une entreprise, notamment en matière de recrutement, de gestion des congés, des compétences et des employés.

---

## 🎯 Thématique

Ce projet s’inscrit dans la thématique **Ressources Humaines (RH)** et répond à plusieurs problématiques :
- La gestion complexe des employés à travers différents services.
- La planification des recrutements et le suivi des candidats.
- L’automatisation du suivi des congés et des réunions.
- L’évaluation continue des compétences.

---

## 🧩 Modules Fonctionnels

🧑‍💼 **Utilisateur**  
- Authentification, inscription, rôles (RH, Employé, Candidat).
- Réinitialisation de mot de passe.
- Activation/désactivation de comptes.

📄 **Recrutement**  
- Gestion des offres d’emploi.
- Candidature, tests techniques, suivi des résultats.

📑 **Contrat & Service**  
- Création de contrats.
- Gestion des services et affectation des employés.

🗓️ **Congé**  
- Demande de congé par les employés.
- Validation par un responsable RH.

📆 **Réunion**  
- Planification et organisation de réunions d’équipe.
- Reservation des salles

📈 **Gestion des compétences**  
- Évaluation des compétences techniques .
- Suivi des performances.
- gestion de formations.

---

## ⚙️ Installation du projet

Voici les étapes pour installer le projet en local à partir du dépôt Git :

```bash
# 1. Cloner le dépôt
git clone https://github.com/votre-utilisateur/nextgenhr.git
cd nextgenhr

# 2. Installer les dépendances PHP via Composer
composer install

# 3. Copier le fichier d’environnement
cp .env .env.local

# 4. Configurer votre base de données dans .env.local
# Exemple :
# DATABASE_URL="mysql://root:password@127.0.0.1:3306/nextgenhr"

# 5. Créer la base de données
php bin/console doctrine:database:create

# 6. Générer le schéma de la base de données
php bin/console doctrine:migrations:migrate

# 7. Lancer le serveur local Symfony
symfony server:start
