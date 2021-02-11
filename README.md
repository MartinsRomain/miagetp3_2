*Membre de l'équipe*
* Yanis DE SOUSA ALVES
* Romain MARTINS
* Hamza GOUDJIL
* Gweltaz COLLET

*Question 1*
* symfony new tp3_2 --full

*Question 2*
* symfony console doctrine:database:create
* symfony console make:entity Annonce
* php bin/console make:migration
* php bin/console doctrine:migrations:migrate

*Question 3*
* symfony composer require orm-fixtures --dev
* symfony console make:fixture
* symfony composer require fakerphp/faker
* php bin/console doctrine:fixtures:load

*Question 4*
* symfony console make:controller pages
* si une erreur de "readonly database" -> chmod -R 777 var

*Question 5*
* symfony composer require cebe/markdown

*Question 6*
* Aucune commande utilisée

*Question 7*
* Aucune commande symfony, seulement installation de material design
* npm i material-components-web

*Question8*
* symfony console make:user
* symfony console make:migration
* php bin/console doctrine:migrations:migrate
* symfony console make:auth
* symfony console make:reg

*Question 9*
* symfony console make:entity Annonce
* ajout d'un attribut "auteur" avec une relation ManyToOne
* php bin/console make:migration
* php bin/console doctrine:migrations:migrate
* symfony console doctrine:fixtures:load


