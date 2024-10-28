Some utility scripts :

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
