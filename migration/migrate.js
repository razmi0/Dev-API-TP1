import { DESCRIPTION_MAX_LENGTH, NAME_MAX_LENGTH, PRODUCT_LIMIT, PRODUCT_ORIGINAL_API_URL, PRODUCT_TARGET_API_URL, } from "./const.js";
console.log("Migration script started...");
console.log(`Will attempt to migrate ${PRODUCT_LIMIT} products...`);
const buildTargetProduct = (product) => {
    console.log("Building target product...");
    return {
        name: product.title.length > NAME_MAX_LENGTH ? product.title.substring(0, 49) : product.title,
        description: product.description.length > DESCRIPTION_MAX_LENGTH
            ? product.description.substring(0, 65000)
            : product.description,
        prix: product.price,
    };
};
const getOriginalProducts = async () => {
    console.log("Fetching original products...");
    const response = await fetch(PRODUCT_ORIGINAL_API_URL);
    if (!response.ok) {
        throw new Error(`HTTP error while fetching public API! status: ${response.status} ${response.statusText}`);
    }
    const products = await response.json();
    return products;
};
const sendTargetProduct = async (product) => {
    console.log("Sending target product...");
    const response = await fetch(PRODUCT_TARGET_API_URL, {
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
const t1 = performance.now();
try {
    const products = await getOriginalProducts();
    const targetProducts = products.map(buildTargetProduct);
    const errors = await Promise.allSettled(targetProducts.map(sendTargetProduct))
        .then((results) => results.map((result) => (result.status === "fulfilled" ? result.value : result.reason)))
        .then((errors) => errors.filter((error) => error));
    console.log("Migration script completed!");
    console.log(`Sum up of errors ( ${errors.length} errors):`);
    console.log(errors);
}
catch (error) {
    console.error(error);
}
const t2 = performance.now();
console.log(`Execution time: ${t2 - t1} ms`);
