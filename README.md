# Laravel Application Setup & Run Guide for Manufacturer Project

## 1. Change the env file. put the database name only  
```
DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=laravel

DB_USERNAME=root

DB_PASSWORD=
```

## 2. Run this  
```
php artisan key:generate
```

## 3. Optimize the Application  
Before running the application, optimize configuration and routes for better performance:  
```
php artisan optimize:clear
```

## 4. Serve the Application  
Run the Laravel server with:  
```
php artisan serve
```

## Cache clear  
 
```
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Ensure dependencies are updated:  
```
composer update
```

## If Session creates problem, run this cmd  
```
php artisan session:table

php artisan cache:table
php artisan migrate
php artisan cache:clear
```

++++ shizan ++++
