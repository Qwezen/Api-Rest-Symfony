# Api-Rest-Symfony
Projet Api Rest Symfony - Licence - Pour C.Haller.

# Stack
Method : Api Rest
Framework : Symfony
Outils : Postman, Mailer.

## TP: Créer une API Symfony

### Objectif :
Cet exercice a pour but de ce familiariser avec la mise en place d'une API RESTful avec Symfony.

### Consignes:
Développer une API pour une application permettant de récupérer des informations sur des jeux vidéos.

### Partie 1:

Les entitées seront :

- VideoGame (title, releaseDate, description).
- Category (name).
- Editor (name, country).

Il faudra mettre en place :

- Des fichiers de migrations
- Des CRUD pour chacun des entités avec gestion des FK.
  - Avec vérification du format des données (Asserts)
- De l’authentification, seul un ADMIN peut éditer, créer ou supprimer
- Un CRUD pour la table User
- Générer un jeu d’essai pertinent à l’aide des DataFixtures
- Gérer les exceptions
- De la pagination avec gestion du cache
- Documenter votre API à l’aide Postman ou d’un Swagger (Nelmio)


### Partie 2:

Il faudra mettre en place :

- Ajouter un booléen “subcription_to_newsletter” à la table user
  - Mettre à jour l’édition d’un Utilisateur en conséquence
- Créer une “console commands” pour envoyer un Email à tous les utilisateurs ayant souscrit à la newsletter
  - Le mail doit contenir un template twig avec tous les jeux qui sorte dans les 7 prochains jours
- Mettre en place un “Scheduler”, pour envoyer le mail précédents tous les lundi à 8h30.
- Ajouter une coverImage à la table “VideoGame” afin d’ajouter la jaquette.
- Faire le README
