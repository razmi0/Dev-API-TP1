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

    // run all curl tests scripts
    "curl:run": "php ./curl/<filename>.php && php ./curl/<filename>.php ect",
    // Only mac and linux systems (.sh files) :

    "watch:server": "./bin/watch_access_log.sh",
    "watch:error": "./bin/watch_error_log.sh",
  },
```
