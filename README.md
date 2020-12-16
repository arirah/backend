###Installation

Run composer command  

`composer install`

Create `.env` file from `.env.example` file.

#####Database  Configure
 Set environment database parameters like below:
##for mysql 
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=picfix
DB_USERNAME=root
DB_PASSWORD=
```
## For sqlite
Comments all variables , only set 

``DB_CONNECTION=sqlite``

##### Migration

Run following commands 

`` php artisan migrate `` 

``composer dumpautoload``

``php artisan cache:clear``

``php artisan db:seed``

Once migration command completed run 

``php artisan serve`` 

It will start your API endpoint like http://localhost:8000/api
