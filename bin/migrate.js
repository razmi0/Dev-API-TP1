const DELAY_ABORT = 200000;
const PRODUCT_LIMIT = 20;
const PRODUCT_ORIGINAL_API_URL = `https://fakestoreapi.com/products?limit=${PRODUCT_LIMIT}`;
const PRODUCT_TARGET_API_URL = `http://localhost/TP1/api/v1/creer.php`;
const NAME_MAX_LENGTH = 50;
const DESCRIPTION_MAX_LENGTH = 65000;

console.log("Migration script started...");
console.log(`Will attempt to migrate ${PRODUCT_LIMIT} products...`);

const buildTargetProduct = (product) => {
  const stringRegex = /^[a-zA-Z0-9-'%,.:\/&()|; \\]+$/g;

  return {
    name: (product.title.length > NAME_MAX_LENGTH ? product.title.substring(0, 49) : product.title).replace(
      stringRegex,
      " "
    ),
    description: (product.description.length > DESCRIPTION_MAX_LENGTH
      ? product.description.substring(0, 65000)
      : product.description
    ).replace(stringRegex, " "),
    prix: product.price,
  };
};

const getOriginalProducts = async () => {
  const response = await fetch(PRODUCT_ORIGINAL_API_URL);
  if (!response.ok) {
    throw new Error(`HTTP error while fetching public API! status: ${response.status} ${response.statusText}`);
  }
  const products = await response.json();
  return products;
};

const loadingAnimation = (text = "Please wait...", delay = 100) => {
  let x = 0;
  const chars = ["⠙", "⠘", "⠰", "⠴", "⠤", "⠦", "⠆", "⠃", "⠋", "⠉"];

  return setInterval(function () {
    process.stdout.write("\r" + chars[x++] + " " + text);
    x = x % chars.length;
  }, delay);
};

const sendTargetProduct = async (product, abortController) => {
  const response = await fetch(PRODUCT_TARGET_API_URL, {
    signal: abortController.signal,
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(product),
  });

  if (!response.ok) {
    return `${response.status} ${response.statusText} ${await response.text()}`;
  }
};

// Main
// --
const t1 = performance.now();
try {
  console.log("Fetching original products...");
  const products = await getOriginalProducts();

  console.log("Building target product...");
  const targetProducts = products.map(buildTargetProduct);

  const abortController = new AbortController();

  const timeoutFetch = setTimeout(() => {
    abortController.abort();
  }, DELAY_ABORT);

  console.log("Sending target products...");

  const loading = loadingAnimation();

  const errors = await Promise.allSettled(targetProducts.map((product) => sendTargetProduct(product, abortController)))
    .then((results) => results.map((result) => (result.status === "fulfilled" ? result.value : result.reason)))
    .then((errors) => errors.filter((error) => error))
    .finally(() => {
      clearTimeout(timeoutFetch);
      clearInterval(loading);
    });

  console.log("Migration script completed!");

  console.log(`Sum up of errors ( ${errors.length} errors):`);
  console.log(errors);
} catch (error) {
  console.error(error);
}
const t2 = performance.now();
console.log(`Execution time: ${t2 - t1} ms`);
