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

Sets up the passport files and database fields (needed for authentication)
~~~~
php artisan passport:install 
~~~~

Seed **after** installing passport for correctly stored passwords
```$xslt
php artisan db:seed
```

This creates 3 verified accounts.
- 1 Admin user (Can CRUD clients, managers, teams & users)
  - admin@retrope.com
  - secret

- 1 Manager user (Can CRUD teams, retrospectives & users)
  - manager@retrope.com
  - secret
  
- 1 Employee user (Can CRUD actions, feedback & votes)
  - employee@retrope.com
  - secret
  
Run migrations for the test database (make sure to create database named retrope_test first)
```$xslt
php artisan migrate --database=mysql_testing
```

Run seed (creating roles) for test database 
```$xslt
php artisan db:seed --class=RoleSeeder --database=mysql_testing
```

Check to see if the tests are A OK!
```$xslt
phpunit
```

### If you have problems running the tests
Make sure to run these every time after you run `php artisan optimize`!

Clears the caches
```$xslt
php artisan config:clear
php artisan cache:clear
```

and try again
```$xslt
phpunit
```