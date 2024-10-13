# MIGRATION CMD

This node cmd call a public api, get the data, transform it and sent it to TP1 db_labrest using local API.
You can change the public API, the local API, the limit of data fetched here: [const.ts](./source/const.ts). Default values are:

```typescript
export const PRODUCT_LIMIT = 100 as const;
export const PRODUCT_ORIGINAL_API_URL = `https://fakestoreapi.com/products?limit=${PRODUCT_LIMIT}` as const;
export const PRODUCT_TARGET_API_URL = `http://localhost/TP1/api/v1/creer.php` as const;
export const NAME_MAX_LENGTH = 50 as const;
export const DESCRIPTION_MAX_LENGTH = 65000 as const;
```

Once a change to the code is made in a ts file, place yourself in the root directory (./migrate) and please execute the following command to compile the code to js:

```bash
tsc # or tsc --watch to compile on save
node migrate.js
```

To execute the migration :

```bash
node migrate.js
```
