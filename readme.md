# RETROPE API

### Setup

Installs all the needed packages
```$xslt
composer install
```

Runs all the migrations
```
php artisan migrate
```
Creates 3 verified accounts. <br>
- 1 Admin user (Can CRUD clients, managers, teams & users)
- 1 Manager user (Can CRUD teams, retrospectives & users)
- 1 Employee user (Can CRUD actions, feedback & votes)

```$xslt
php artisan seed
```

Sets up the passport files and database fields (needed for authentication)
```
php artisan passport:install 
```

Check to see if the tests are A OK!
```$xslt
phpunit tests
```