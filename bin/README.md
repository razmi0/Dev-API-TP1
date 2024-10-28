# Bin

use :

```bash
composer run-script <script_name>
```

Some utility scripts [see](../composer.json) :

```json
"scripts": {
    // db scripts
    "db:truncate": "php ./bin/truncate.php",

    // migration scripts ( from front-end)
    "db:migrate": "node ./bin/migration/migrate.js",

    // Only mac and linux systems (.sh files) :

    "watch:server": "./bin/watch_access_log.sh",
    "watch:error": "./bin/watch_error_log.sh"
  },
```
