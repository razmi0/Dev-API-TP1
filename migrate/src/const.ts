export const PRODUCT_LIMIT = 100 as const;
export const PRODUCT_ORIGINAL_API_URL = `https://fakestoreapi.com/products?limit=${PRODUCT_LIMIT}` as const;
export const PRODUCT_TARGET_API_URL = `http://localhost/TP1/api/v1/creer.php` as const;

export const NAME_MAX_LENGTH = 50 as const;
export const DESCRIPTION_MAX_LENGTH = 65000 as const;
