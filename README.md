# TP1 Basic CRUD API

hi

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
    "watch:error": "./bin/watch_error_log.sh",
    "curl:run": "php ./curl/<filename>.php && php ./curl/<filename>.php ect",
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
        ─ migrate.js
        ─ README.md
        ─ truncate.php
        ─ watch_access_log.sh
        ─ watch_error_log.sh
    📁 core
        ─ Endpoint.php
    📁 curl
        📁 scripts
            ─ creer.php
            ─ lire_des.php
            ─ lire_un.php
            ─ lire.php
            ─ modifier.php
            ─ supprimer.php
        ─ README.md
        ─ Session.php
        ─ Test.php
    📁 doc
    📁 http
        ─ Error.php
        ─ Payload.php
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
        📁 validator
            ─ Constant.php
            ─ Readme.md
            ─ Validator.php
            ─ ValidatorResult.php
        ─ .DS_Store
        ─ Middleware.php
    📁 model
        📁 dao
            ─ Connection.php
            ─ ProductDao.php
        📁 entity
            ─ Product.php
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
    ─ composer.json
    ─ index.php
    ─ README.md
```
