# TP1 Basic CRUD API

## Installation

This API is documented using Swagger-PHP. To generate the documentation, you need to install the swagger-php package :

```bash
composer install
```

You can run all this scripts to improve your development experience :

```json
"scripts": {
    "db:truncate": "php ./bin/truncate.php",
    "db:migrate": "node ./bin/migration/migrate.js",

    // Only mac and linux systems (.sh files) :

    "watch:server": "./bin/watch_access_log.sh",
    "watch:error": "./bin/watch_error_log.sh"
  },
```

## Project structure

```plaintext
📁 TP1
    📁 api
        📁 v1
            ─ creer.php
            ─ lire_des.php
            ─ lire_un.php
            ─ lire.php
            ─ modifier.php
            ─ supprimer.php
    📁 bin
        📁 migration
            ─ const.js
            ─ migrate.js
        ─ truncateDb.php
        ─ watch_access_log.sh
        ─ watch_error_log.sh
    📁 controller
        ─ Controller.php
    📁 curl
        ─ creer.php
        ─ Curl.php
        ─ lire_des.php
        ─ lire_un.php
        ─ lire.php
        ─ modifier.php
        ─ supprimer.php
    📁 http
        ─ Error.php
        ─ Request.php
        ─ Response.php
    📁 images
        ─ theme-icon.svg
    📁 js
        📁 src
            ─ APIFetch.ts
            ─ const.ts
            ─ dom.ts
            ─ index.ts
            ─ storage.ts
            ─ syncId.ts
            ─ theme-toggle.ts
            ─ types.ts
        ─ README.md
        ─ tsconfig.json
    📁 middleware
        ─ Middleware.php
    📁 model
        📁 dao
            ─ Connection.php
            ─ ProductDao.php
        📁 entities
            ─ Product.php
        📁 schema
            📁 Validator
                ─ ArrayValidator.php
                ─ ComplexValidator.php
                ─ RangeValidator.php
                ─ TypeValidator.php
                ─ ValidatorError.php
                ─ ValidatorInterface.php
                ─ ValidatorResult.php
            ─ Core.php
            ─ Schema.php
            ─ Template.php
        ─ Constant.php
    📁 sql
        ─ bdd.md
    📁 utils
        📁 migration
            📁 src
                ─ const.ts
                ─ migrate.ts
                ─ type.ts
            ─ const.js
            ─ migrate.js
            ─ README.md
            ─ tsconfig.json
        ─ Console.php
    ─ .gitignore
    ─ .htaccess
    ─ Autoloader.php
    ─ composer.json
    ─ composer.lock
    ─ index.php
    ─ README.md
```
