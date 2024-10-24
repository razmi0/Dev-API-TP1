import {
  DESCRIPTION_MAX_LENGTH,
  NAME_MAX_LENGTH,
  PRODUCT_LIMIT,
  PRODUCT_ORIGINAL_API_URL,
  PRODUCT_TARGET_API_URL,
} from "./const.js";
import type { OriginalProduct, TargetProduct } from "./type.js";

// Start of the migration script
// --

console.log("Migration script started...");
console.log(`Will attempt to migrate ${PRODUCT_LIMIT} products...`);

/**
 *
 * Build a target product ( dblabrest like ) from an original product ( public API like )
 *
 */
const buildTargetProduct = (product: OriginalProduct): TargetProduct => {
  console.log("Building target product...");
  return {
    // name sql column is limited to 50 characters ( VARCHAR(50) )
    // --
    name: product.title.length > NAME_MAX_LENGTH ? product.title.substring(0, 49) : product.title,

    // description sql column is limited to 65,535 characters ( TEXT )
    // --
    description:
      product.description.length > DESCRIPTION_MAX_LENGTH
        ? product.description.substring(0, 65000)
        : product.description,

    prix: product.price,
  };
};

/**
 *
 * Fetch original products from the public API
 *
 */
const getOriginalProducts = async () => {
  console.log("Fetching original products...");
  const response = await fetch(PRODUCT_ORIGINAL_API_URL);
  if (!response.ok) {
    throw new Error(`HTTP error while fetching public API! status: ${response.status} ${response.statusText}`);
  }
  const products: OriginalProduct[] = await response.json();
  return products;
};

/**
 *
 * Send a target product to the dblabrest API
 *
 */
const sendTargetProduct = async (product: TargetProduct) => {
  console.log("Sending target product...");
  const response = await fetch(PRODUCT_TARGET_API_URL, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(product),
  });
  if (!response.ok) {
    // Prepare error message
    // --
    return `${response.status} ${response.statusText} ${await response.text()}`;
  }
};

const t1 = performance.now();

// Main
// --

try {
  // Get original products ( public API like )
  // --
  const products = await getOriginalProducts();

  // Build target products ( dblabrest like )
  // --
  const targetProducts = products.map(buildTargetProduct);

  // Send target products to the dblabrest API and sum up errors as a string[]
  // --
  const errorTxts = await Promise.allSettled(targetProducts.map(sendTargetProduct));

  // Sum up errors ( if fullfilled, the value is null, if rejected, the value is the error message )
  // --
  const results = errorTxts.map((result) => (result.status === "fulfilled" ? result.value : result.reason));

  // Filter errors ( only keep the error messages )
  // --
  const errors = results.filter((error) => error);

  // Log errors
  // --
  console.log("Migration script completed!");
  console.log(`Sum up of errors ( ${errors.length} errors):`);
  console.log(errors);
} catch (error) {
  console.error(error);
}

const t2 = performance.now();
console.log(`Execution time: ${t2 - t1} ms`);

export {};
