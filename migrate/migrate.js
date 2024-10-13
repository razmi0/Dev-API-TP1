import { PRODUCT_LIMIT, PRODUCT_ORIGINAL_API_URL, PRODUCT_TARGET_API_URL } from "./const.js";
console.log("Migration script started...");
console.log(`Will attempt to migrate ${PRODUCT_LIMIT} products...`);
const buildTargetProduct = (product) => {
    console.log("Building target product...");
    return {
        name: product.title,
        description: product.description,
        prix: product.price,
    };
};
const getOriginalProducts = async () => {
    console.log("Fetching original products...");
    const response = await fetch(PRODUCT_ORIGINAL_API_URL);
    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status} ${response.statusText}`);
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
        throw new Error(`HTTP error! status: ${response.status} ${response.statusText} `);
    }
    return await response.json();
};
const t1 = performance.now();
try {
    const products = await getOriginalProducts();
    const targetProducts = products.map(buildTargetProduct);
    const responseStatus = await Promise.all(targetProducts.map(sendTargetProduct));
    console.log(responseStatus);
}
catch (error) {
    console.error(`[ERROR] : ${error}`);
}
const t2 = performance.now();
console.log(`Execution time: ${t2 - t1} ms`);
