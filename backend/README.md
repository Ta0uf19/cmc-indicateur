## Backend

Using [PHP Lumen Framework](https://lumen.laravel.com/)

## Setup
1. In commande line : ```composer install``` to install dependencies
2. Upload database **traceforum.sql** (in the root of the project)
3. (Optional) Add this database view : (if you have already database without view)

##### View of productivity
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

#### View of confidence
```sql 
DROP VIEW IF EXISTS confidence_analytics; 
CREATE VIEW confidence_analytics 
AS 
  SELECT R.user, 
         Ifnull(total_replied, 0)    AS total_replied, 
         Ifnull(total_new_posted, 0) AS total_new_posted, 
         Ifnull(total, 0)            AS total 
  FROM   (SELECT A1.user, 
                 total_replied, 
                 total_new_posted, 
                 ( total_replied * 0.4 + total_new_posted * 0.6 ) AS total 
          FROM   (SELECT utilisateur  AS user, 
                         COUNT(titre) AS 'total_replied' 
                  FROM   transition 
                  WHERE  titre LIKE 'Répondre à un message' 
                  GROUP  BY utilisateur) AS A1 
                 INNER JOIN (SELECT utilisateur  AS user, 
                                    COUNT(titre) AS total_new_posted 
                             FROM   transition 
                             WHERE  titre LIKE 'Poster un nouveau message' 
                             GROUP  BY utilisateur) AS A2 
                         ON A1.user = A2.user) AS L 
         RIGHT JOIN (SELECT utilisateur AS user 
                     FROM   transition 
                     GROUP  BY utilisateur) AS R 
                 ON L.user = R.user

```

4. Rename .env.example to  `.env` config file and configure database
5. Launch the application server
```php
php -S localhost:8000 -t public
```
