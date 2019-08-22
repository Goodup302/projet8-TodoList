
Pour les tâches déjà créées, il faut qu’elles soient rattachées à un utilisateur “anonyme”.

Création/édition d’un utilisateur: il doit être possible de choisir/changer un rôle pour celui-ci.

Roles à créé:
    ROLE_USER (Default role)
    ROLE_ADMIN


Un tâche doit être attachée à un utilisateur
Pour les tâches déjà créées, il faut qu’elles soient rattachées à un utilisateur “anonyme”.

Lors de la modification d’un utilisateur, il est également possible de changer le rôle d’un utilisateur.


Autorisation:
    Gestion des utilisateurs réserver à (ROLE_ADMIN)
    Les tâches ne peuvent être supprimées que par les utilisateurs ayant créé les tâches en questions.
    Les tâches rattachées à l’utilisateur “anonyme” ne peuvent être supprimées uniquement par (ROLE_ADMIN).








A voir:
    Mettre a jour symfony vers une version, stable 3.4



Implémentation de tests automatisés (test unitaires et fonctionnels):

Utiliser ces outils suivants: PHPUnit/Behat/bridge symfony

Prévoirez des données (Fixtures)

Vous prévoirez des données de tests afin de pouvoir prouver le fonctionnement dans les cas explicités dans ce document.

Il vous est demandé de fournir un rapport de couverture de code au terme du projet. Il faut que le taux de couverture soit supérieur à 70%.

Documentation technique
Il vous est demandé de produire une documentation expliquant comment l’implémentation de l'authentification a été faite. Cette documentation se destine aux prochains développeurs juniors qui rejoindront l’équipe dans quelques semaines. Dans cette documentation, il doit être possible pour un débutant avec le framework Symfony de :

comprendre quel(s) fichier(s) il faut modifier et pourquoi ;
comment s’opère l’authentification ;
et où sont stockés les utilisateurs.
Si d’autres informations vous semble importantes d’être mentionnées, n’hésitez pas à le faire.


Configurer de l'intégration continue avec travis-ci

Audit de qualité du code & performance de l'application:
    - la qualité de code (codeclimate.com) et la performance (Blackfire).
    - Récupérer le plus de metrics possible



Etapes de travail:
    - Créez l’ensemble des issues sur le repository Github (https://github.com/username/nom_du_repo/issues/new).
    - Faites vos estimations de l’ensemble de vos issues dans l'ordre.
    - Entamez le développement de l’application et proposez des pull requests pour chacune des fonctionnalités/issues.
    - Vérifiez la qualité ainsi que les performances de votre code à chaque commit.
    - Effectuez une démonstration de l’ensemble de l’application.
    - Préparez l’ensemble de vos livrables et soumettez-les sur la plateforme.


Livrables:
    Lien vers Github/les Issues
    Créer un README (détailler le processus de qualité à utiliser ainsi que les règles à respecter)
    Créé un markdown expliquant comment contribuer au projet (https://github.com/symfony/demo/blob/master/CONTRIBUTING.md)
    Documentation technique sur l’implémentation de l’authentification/autorisation (Format PDF)
    Les fichiers HTML de code coverage de PHPUnit (Couvertureminimum de 70%)
    Le rapport d’audit de qualité de code et de performance (Format PDF)