## Backend

Using [PHP Lumen Framework](https://lumen.laravel.com/)

## Setup

1. Upload database **traceforum.sql**
2. Add this database view : 
```sql
    DROP view IF EXISTS productivity_analytics; 
    CREATE view productivity_analytics 
    AS 
      SELECT Count(utilisateur) / (SELECT Count(*) 
                                   FROM   (SELECT utilisateur 
                                           FROM   transition 
                                           GROUP  BY utilisateur) AS U) AS 
             'participation_rate', 
             Sum(nbr)                                                   AS 
                'activity_total', 
             date 
      FROM   (SELECT Count(*) AS 'Nbr', 
                     utilisateur, 
                     date
              FROM   `transition` 
              WHERE  titre LIKE '%Afficher une structure (cours/forum)%' 
              GROUP  BY date, 
                        utilisateur) AS T 
      GROUP  BY T.date; 

  ```

3. Update `.env` config file
3. Launch the application server
```php
php -S localhost:8000 -t public
```
