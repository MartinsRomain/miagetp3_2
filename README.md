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