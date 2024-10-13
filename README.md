# TP1 Basic CRUD API

## Project structure

```css
📁 TP1
    📁 api
        📁 v1
            ─ creer.php
            ─ lire_un.php
            ─ lire.php
            ─ modifier.php
            ─ supprimer.php
    📁 curl
    📁 Error
    📁 js
    📁 model
        📁 dao
            ─ Connection.php
            ─ ProduitDao.php
        📁 entities
            ─ Produit.php
    📁 mysql
        ─ bdd.txt
    ─ .gitignore
    ─ .htaccess
    ─ Autoloader.php
    ─ README.md
```

## TODO

TP1 :

- [x] (#1)Remplacer mysqli par PDO
- [x] (#2)Incorporer dans mon entité un validateur pour les setters
  - Note : un service a été créé [ici](./model/services/ProduitService.php)
- [ ] choose a better type for the id and the date in the entity
- [x] add message properties to json response object
  - Note : une classe Response a été créée [ici](./utils/Response.php)
