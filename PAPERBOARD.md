# MAJ Symfony :heavy_check_mark:
    Mettre a jour symfony vers une version, stable 4.4 ✔


# Taches
    - Pour les tâches déjà créées, il faut qu’elles soient rattachées à un utilisateur “anonyme”. ✔
    - Un tâche doit être attachée à un utilisateur ✔
    - Les tâches rattachées à l’utilisateur “anonyme” ne peuvent être supprimées uniquement par (ROLE_ADMIN).


# User
    - Ajouter un system de role ✔
    - Lors de la modification d’un utilisateur, il est également possible de changer le rôle d’un utilisateur.

# Autorisation
    - Roles:
        - ROLE_USER (Default role) ✔
        - ROLE_ADMIN ✔
        - ANONYMOUS ✔
    - Gestion des utilisateurs réserver à (ROLE_ADMIN).
    - Les tâches rattachées à l’utilisateur “anonyme” ne peuvent être supprimées uniquement par (ROLE_ADMIN).
    - Les tâches ne peuvent être supprimées que par les utilisateurs ayant créé les tâches en questions.
   

# Fixtures
    - Prévoire des données de tests emglobant tout les cas explicités dans ce document. ✔


# Implementation de tests automatises (test unitaires et fonctionnels)
    - Utiliser ces outils suivants: PHPUnit/Behat/bridge symfony
    - Code coverage soit supérieur à 70%.


# Documentation technique
    - Destiner au prochain dévelopeur junior
    - Comprendre quel(s) fichier(s) il faut modifier et pourquoi
    - Comment s’opère l’authentification ;
    - Où sont stockés les utilisateurs.
    - Si d’autres informations vous semble importantes d’être mentionnées, n’hésitez pas à le faire.


# Audit de qualite du code & performance de l'application
    - la qualité de code (codeclimate.com) et la performance (Blackfire).
    - Récupérer le plus de metrics possible


# Integration continue (CI)
    - Configurer de l'intégration continue avec travis-ci


# Livrables
    - Lien vers Github
    - Créer un README (détailler le processus de qualité à utiliser ainsi que les règles à respecter)
    - Créé un markdown expliquant comment contribuer au projet (https://github.com/symfony/demo/blob/master/CONTRIBUTING.md)
    - Documentation technique sur l’implémentation de l’authentification/autorisation (Format PDF)
    - Les fichiers HTML de code coverage de PHPUnit (Couverture minimum de 70%)
    - Le rapport d’audit de qualité de code et de performance (Format PDF)