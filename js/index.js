console.log("Starting the app...");
// API Endpoints
// --
const API_URL = "http://localhost/TP1/api/v1";
const API_READ_ALL_ENDPOINT = `${API_URL}/lire.php`;
const API_READ_ONE_ENDPOINT = `${API_URL}/lire_un.php`;
const API_DELETE_ONE_ENDPOINT = `${API_URL}/supprimer.php`;
const API_CREATE_ONE_ENDPOINT = `${API_URL}/creer.php`;
// DOM ELEMENTS
//--
const inputSections = Array.from(document.querySelectorAll("[data-endpoint]"));
const outputSections = inputSections.map((section) => section.nextElementSibling);
const insertText = ({ ctn, text, code = null, error = false }) => {
    const prefix = error ? "[ERROR] : " : "";
    const suffix = code ? `-- Code ${code}` : "";
    const className = error ? "pico-color-red-500" : "";
    ctn.innerHTML = `
        <div>
            <p class="${className}">${prefix} ${text} ${suffix}</p>
        </div>
    `;
};
/**
 *
 * @param ctn The container where the data will be inserted
 * @param data The data to insert
 *
 */
const insertData = (ctn, data) => {
    console.log("Inserting data...");
    const table = document.createElement("table");
    const thead = document.createElement("thead");
    const tbody = document.createElement("tbody");
    const ths = ["ID", "Nom", "Description", "Prix", "Date de création"].map((text) => {
        const th = document.createElement("th");
        th.textContent = text;
        return th;
    });
    const tds = data.map((product) => {
        const tr = document.createElement("tr");
        const values = Object.values(product);
        const tds = values.map((value) => {
            const td = document.createElement("td");
            td.textContent = value;
            return td;
        });
        tr.append(...tds);
        return tr;
    });
    tbody.append(...tds);
    thead.append(...ths);
    table.append(thead, tbody);
    ctn.append(table);
};
/**
 *
 * Setup the read all logic
 *
 */
const setupReadAll = () => {
    console.log("Setting up read all logic...");
    const readSection = inputSections.find((section) => section.dataset.endpoint === "read");
    const readAllButton = readSection.querySelector("button");
    const outputSection = readSection.nextElementSibling;
    readAllButton.addEventListener("click", async () => {
        console.log("Fetching data...");
        const response = await fetch(API_READ_ALL_ENDPOINT);
        const json = await response.json();
        if (json.error)
            insertText({ ctn: outputSection, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: outputSection, text: json.message, code: response.status });
        if (json.data)
            insertData(outputSection, json.data);
    });
};
/**
 *
 * Setup the read one logic
 *
 */
const setupReadOne = () => {
    console.log("Setting up read one logic...");
    const readOneSection = inputSections.find((section) => section.dataset.endpoint === "read-one");
    const readOneButton = readOneSection.querySelector("button");
    const outputSection = readOneSection.nextElementSibling;
    const readOneInput = readOneSection.querySelector("input");
    readOneInput.addEventListener("input", () => {
        readOneInput.value.length > 0
            ? readOneButton.removeAttribute("disabled")
            : readOneButton.setAttribute("disabled", "");
    });
    readOneButton.addEventListener("click", async (e) => {
        e.preventDefault();
        console.log("Fetching data...");
        const id = readOneInput.value;
        if (!id) {
            insertText({ ctn: outputSection, text: "L'id est obligatoire" });
            return;
        }
        const apiURLWithId = `${API_READ_ONE_ENDPOINT}?id=${id}`;
        const response = await fetch(apiURLWithId);
        const json = await response.json();
        if (json.error)
            insertText({ ctn: outputSection, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: outputSection, text: json.message, code: response.status });
        if (json.data)
            insertData(outputSection, json.data);
    });
};
/**
 *
 * Setup the delete logic
 *
 */
const setupDelete = () => {
    console.log("Setting up delete logic...");
    const deleteOneSection = inputSections.find((section) => section.dataset.endpoint === "delete");
    const deleteOneButton = deleteOneSection.querySelector("button");
    const outputSection = deleteOneSection.nextElementSibling;
    const deleteOneInput = deleteOneSection.querySelector("input");
    deleteOneInput.addEventListener("input", () => {
        deleteOneInput.value.length > 0
            ? deleteOneButton.removeAttribute("disabled")
            : deleteOneButton.setAttribute("disabled", "");
    });
    deleteOneButton.addEventListener("click", async (e) => {
        e.preventDefault();
        console.log("Deleting data...");
        const id = deleteOneInput.value;
        if (!id) {
            insertText({ ctn: outputSection, text: "L'id est obligatoire" });
            return;
        }
        const fetchOptions = {
            method: "DELETE",
            body: JSON.stringify({ id }),
            headers: {
                "Content-Type": "application/json",
            },
        };
        const response = await fetch(API_DELETE_ONE_ENDPOINT, fetchOptions);
        const json = await response.json();
        if (json.error)
            insertText({ ctn: outputSection, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: outputSection, text: json.message, code: response.status });
        if (json.data)
            insertData(outputSection, json.data);
    });
};
/**
 *
 * Setup the create logic
 *
 */
const setupCreateOne = () => {
    console.log("Setting up create logic...");
    const createOneSection = inputSections.find((section) => section.dataset.endpoint === "create");
    const createOneButton = createOneSection.querySelector("button");
    const outputSection = createOneSection.nextElementSibling;
    const inputs = Array.from(createOneSection.querySelectorAll("input"));
    inputs.forEach((input) => {
        input.addEventListener("input", () => {
            const isFormValid = inputs.every((input) => input.value.length > 0);
            isFormValid ? createOneButton.removeAttribute("disabled") : createOneButton.setAttribute("disabled", "");
        });
    });
    createOneButton.addEventListener("click", async (e) => {
        e.preventDefault();
        console.log("Creating data...");
        const clientData = inputs.reduce((acc, input) => {
            acc[input.name] = input.value;
            return acc;
        }, {});
        const fetchOptions = {
            method: "POST",
            body: JSON.stringify(clientData),
            headers: {
                "Content-Type": "application/json",
            },
        };
        const response = await fetch(API_CREATE_ONE_ENDPOINT, fetchOptions);
        const json = await response.json();
        if (json.error)
            insertText({ ctn: outputSection, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: outputSection, text: json.message, code: response.status });
        if (json.data)
            insertData(outputSection, json.data);
    });
};
/**
 * Run the app
 */
const run = () => {
    setupReadAll();
    setupReadOne();
    setupDelete();
    setupCreateOne();
};
run();
