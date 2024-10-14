# TP1 Basic CRUD API

- notes
  - controller => dao => base de données
  - chaque transfert entre couche est objet ( entité)

## Project structure

## TODO

TP1 :

- [x] (#1)Remplacer mysqli par PDO
- [x] (#2)Incorporer dans mon entité un validateur pour les setters
  - Note : un service a été créé [ici](./model/services/ProduitService.php)
- [ ] choose a better type for the id and the date in the entity
- [x] add message properties to json response object
  - Note : une classe Response a été créée [ici](./utils/Response.php)
  - Reserver la manipulation de Reponse à la classe Controller
- [ ] (#3)Lisser les commentaires
- [ ] (#4) Reflechir a refactoriser Controller
- [ ] (#5) Nouveau design
  - [ ] (#5.1) Creer dossier controller et y mettre les controllers
  - [ ] (#5.2) Mettre le running de l'api dans le endpoint
  - [ ] (#5.3) le running de l'api consiste en :
    - Appeler le controller correspondant => Error => throw MethodNotAllowedException (405)
    - Appeler le service de validation => Error => throw BadRequestException (400)
    - Appeller le DAO => Error => throw InternalServerErrorException (500) / NotFoundException (404)
    - Appeller Reponse => Error => throw InternalServerErrorException (500)
- [x] (#6) Bouger validation services dans une classe Validator
  - [x] (#6.1) Creer une classe Validator et ses methodes
  - [x] (#6.2) lire.php
  - [x] (#6.4) creer.php
  - [x] (#6.5) modifier.php
  - [x] (#6.6) supprimer.php

## TODO ANNEXE

- reflechir a une fonxtion de construction de query update sur-mesure.
