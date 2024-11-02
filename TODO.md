# Todo

## Top priority

- [ ] (#15) Implémenter swagger

## No top priority

Error :

- [ ] (#10) Refacto Error en statique 100%
- [ ] (#11) Supprimer toutes traces de la propriété $location dans les classes en particulier dans Error, Request, Response

## meh

- [ ] (#7) Reflechir à creer une classe QueryBuilder pour les requetes SQL
- [ ] (#8) Reflechir à faire en sorte que le controller accepte de traiter plusieurs méthodes HTTP par endpoint

## History

<_API endpoint are finnally done in commit : 9872f770f4815b03a5127703cff4e60e3b2456a1 👍_>

- [x] (#1)Remplacer mysqli par PDO
- [x] (#2)Incorporer dans mon entité un validateur pour les setters
- [x] choose a better type for the id and the date in the entity
- [x] add message / error / data properties to json response object
- [x] Creer un namespace HTTP pour Reponse, Request, Error
- [x] Charger Error en methodes spécifiques selon les code de réponse HTTP (404, 500, 200, 201, 400)
- [x] (#3)Lisser les commentaires
- [x] (#4) Reflechir a refactoriser Controller
- [x] (#5) Nouveau design Controller
  - [x] (#5.1) Creer dossier controller et y mettre un controller assumant des mecanismes type middlewares commun à tous les endpoints
  - [x] (#5.2) Mettre le running de l'api dans le endpoint
  - [x] (#5.3) Mettre le running de l'api dans le endpoint mais dans un closure bindé au Controller
- [x] (#6) Creer systeme de validation des données entrantes via une classe Schema et un systeme de templating de règles de validation
  - [x] (#6.1) Creer une classe Schema et ses methodes
  - [x] (#6.2) Creer une class Template et ses methodes pour verifier l'integrité du Schema
  - [x] (#6.3) Creer une interface ValidatorInterface et contraintre les Validators à implementer cette interface
  - [x] (#6.4) Intégrer le systeme de validation dans le controller ( middleware like)
  - [x] (#6.5) Implémenter TypeValidator, ComplexValidator, RangeValidator pour l'instant
- [x] (#9) Mettre le handler du controller directement dans la method run()
- [x] (#16) Rennomer Middleware/Controller => ProductMiddleware/ProductController
- [x] (#12) Modifier les endpoints dans les script curl car les redirections sont maintenant en place
- [x] (#13) Rajouter un call sur le endpoint read_many dans les scripts curl
- [x] (#14) Remplacer certaines redirections par /produits/
