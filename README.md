ToDoList
========

Base du projet #8 : Améliorez un projet existant
https://openclassrooms.com/projects/ameliorer-un-projet-existant-1


Code coverage cmd:  
bin/phpunit --coverage-html codecoverage/

Osteoform symfony 4  application

After install, set globals on .env.local:
https://symfony.com/doc/current/configuration.html
https://symfony.com/doc/current/email.html#configuration
https://symfony.com/doc/current/doctrine.html
```yaml
APP_ENV=prod
DATABASE_URL=mysql://login:password@127.0.0.1:3306/osteo
MAILER_URL=smtp://localhost:25?encryption=ssl&auth_mode=login&username=&password=
EMAIL_ADMIN=admin@exemple.fr
EMAIL_WEBMASTER=webmaster@exemple.fr
```

- Install Project

https://symfony.com/doc/current/deployment.html
```shell
git clone https://username:password@github.com/ocsalis/Osteoform.git .
chown www-data -R *
chmod -R 777 *
composer install --optimize-autoloader
php bin/console doctrine:database:create -n
php bin/console doctrine:schema:create -n
php bin/console doctrine:fix:load -n --group=prod
php bin/console cache:clear -e prod -n --no-debug

```

- Update : prod
```shell
git reset --hard HEAD
git pull origin master
rm -rf vendor/
rm -rf var/cache
composer install -o
php bin/console cache:clear

```

- Update : dev
```shell
git reset --hard HEAD
git pull origin dev
rm -rf vendor/
rm -rf var/cache
composer install -o
php bin/console cache:clear

```


- Install Project : dev
```shell
git clone https://username:password@github.com/ocsalis/Osteoform.git .
chown www-data -R *
chmod -R 777 *
composer install
php bin/console doctrine:database:create -n
php bin/console doctrine:schema:create -n
php bin/console doctrine:fix:load -n

```

- Rester Fixtures
```shell
php bin/console doctrine:database:drop --force -n
php bin/console doctrine:database:create -n
php bin/console doctrine:schema:create -n
php bin/console doctrine:fix:load -n

`````

- Config SCSS File Watcher PHPStorm:  
    Program: C:\Users\%username%\AppData\Roaming\npm\node-sass.cmd  
    Arguments : $FileName$ $FileParentDir$\css\$FileNameWithoutExtension$.css  
    Output paths to refrech: $FileDir$FileNameWithoutExtension$.css.map  