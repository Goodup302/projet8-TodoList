# ToDoList

[![Maintainability](https://api.codeclimate.com/v1/badges/48dfc02a326d3d1acd92/maintainability)](https://codeclimate.com/github/Goodup302/projet8-TodoList/maintainability)

Projet #8 :
[Améliorez un projet existant](https://openclassrooms.com/projects/ameliorer-un-projet-existant-1)


## Install Project

https://symfony.com/doc/current/configuration.html  
https://symfony.com/doc/current/email.html#configuration  
https://symfony.com/doc/current/doctrine.html  
https://symfony.com/doc/current/deployment.html  

- Install
```shell
git clone https://github.com/Goodup302/projet8-TodoList.git .
composer install -o
```

- Set globals on .env.local and .env.test:
```yaml
APP_ENV=prod
DATABASE_URL=mysql://login:password@127.0.0.1:3306/dbname
MAILER_URL=gmail://user:pass@localhost
```

- Create BDD
```yaml
php bin/console doctrine:database:create -n
php bin/console doctrine:schema:create -n
php bin/console doctrine:fix:load -n
php bin/console cache:clear
```

- Rester Fixtures
```shell
php bin/console doctrine:database:drop --force -n
php bin/console doctrine:database:create -n
php bin/console doctrine:schema:create -n
php bin/console doctrine:fix:load -n
`````

## Code coverage: 

Html Code coverage files is in "/codecoverage"

- Lancer les tests 
```shell
php bin/console doctrine:fix:load -n
bin/phpunit --coverage-html codecoverage/
```

## Other Documents: 
Other Documents is in "/documents".  
Here you will find technical documentation and code and performance auditing.