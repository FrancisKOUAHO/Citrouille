# Citrouille

Prérequi

*Prérequis sur votre machine pour le bon fonctionnement de ce projet :

    PHP Version 7.4.11 Installer PHP -- Mettre à jour PHP en 7.4 (Ubuntu)
    MySQL Installer MySQL ou Installer MariaDB
    Symfony version 5.0 minimum avec le CLI(Binaire) Symfony Installer Symfony -- Installer Binaire Symfony
    Composer Installer Composer
    Npm Installer Npm
    Yarn Installer Yarn

Installation

Après avoir cloné le projet avec git clone https://github.com/DimitriKft/myp_symfony.git

Exécutez la commande cd myp_symfony pour vous rendre dans le dossier depuis le terminal.

Ensuite, dans l'ordre taper les commandes dans votre terminal :

    1 composer install afin d'installer toutes les dépendances composer du projet.

    2 installer la base de donnée MySQL. Pour paramétrer la création de votre base de donnée, rdv dans le fichier .env du projet, et modifier la variable d'environnement selon vos paramètres :

    DATABASE_URL=mysql://User:Password@127.0.0.1:3306/nameDatabasse?serverVersion=5.7

    Puis exécuter la création de la base de donnée avec la commande : symfony console doctrine:database:create

    3 Exécuter la migration en base de donnée : symfony console doctrine:migration:migrate ou importer le fichier citrouille.sql

    4 Exécuter les dataFixtures avec la commande : php bin/console doctrine:fixtures:load

    5 Voir avant le css avant compilation : yarn run encore production --watch

    6 Vous pouvez maintenant accéder à votre portfolio en vous connectant au serveur : symfony server:start

Démarrage

Une fois sur l'application, il ne vous reste plus qu'a vous connecter /login

Si vous avez importer le fichier citrouille.sql

loger vous avec :
    - login : Francis
    - mot de passe : test1


