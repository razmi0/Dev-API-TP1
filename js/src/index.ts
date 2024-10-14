console.log("Starting the app...");

// API Endpoints
// --
const API_URL = "http://localhost/TP1/api/v1" as const;
const API_READ_ALL_ENDPOINT = `${API_URL}/lire.php` as const;
const API_READ_ONE_ENDPOINT = `${API_URL}/lire_un.php` as const;
const API_DELETE_ONE_ENDPOINT = `${API_URL}/supprimer.php` as const;
const API_CREATE_ONE_ENDPOINT = `${API_URL}/creer.php` as const;

// Types
// --
type APIResponse = {
  error: string | null;
  message: string | null;
  data: Product[];
};

type Product = {
  id: string;
  name: string;
  description: string;
  price: string;
  data_creation: string;
};

// DOM ELEMENTS
//--

const inputSections = Array.from(document.querySelectorAll("[data-endpoint]")) as HTMLElement[];
const outputSections = inputSections.map((section) => section.nextElementSibling as HTMLElement);

/**
 *
 * @param ctn The container where the message will be inserted
 * @param text The message text
 * @param code The message code
 * @param options { error: boolean } If the message is an error or not
 *
 */
const insertText = (ctn: HTMLElement, text: string, code: number, options: { error: boolean } = { error: false }) => {
  const colorClass = options.error ? "pico-color-red-500" : "";
  const prefix = options.error ? "[ERROR] : " : "";
  ctn.innerHTML = `
        <div>
            <p class="${colorClass}">${prefix}${text} -- Code ${code}</p>
        </div>
    `;
};

/**
 *
 * @param ctn The container where the data will be inserted
 * @param data The data to insert
 *
 */
const insertData = (ctn: HTMLElement, data: Product[]) => {
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
  const readAllButton = readSection.querySelector("button") as HTMLButtonElement;
  const outputSection = readSection.nextElementSibling as HTMLElement;

  readAllButton.addEventListener("click", async () => {
    console.log("Fetching data...");
    const response = await fetch(API_READ_ALL_ENDPOINT);
    const json: APIResponse = await response.json();

    if (json.error) insertText(outputSection, json.error, response.status, { error: true });
    if (json.message) insertText(outputSection, json.message, response.status);
    if (json.data) insertData(outputSection, json.data);
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
  const readOneButton = readOneSection.querySelector("button") as HTMLButtonElement;
  const outputSection = readOneSection.nextElementSibling as HTMLElement;
  const readOneInput = readOneSection.querySelector("input") as HTMLInputElement;

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
      insertText(outputSection, "L'id est obligatoire", 0);
      return;
    }

    const apiURLWithId = `${API_READ_ONE_ENDPOINT}?id=${id}`;

    const response = await fetch(apiURLWithId);
    const json: APIResponse = await response.json();

    console.log(json);

    if (json.error) insertText(outputSection, json.error, response.status, { error: true });
    if (json.message) insertText(outputSection, json.message, response.status);
    if (json.data) insertData(outputSection, json.data);
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
  const deleteOneButton = deleteOneSection.querySelector("button") as HTMLButtonElement;
  const outputSection = deleteOneSection.nextElementSibling as HTMLElement;
  const deleteOneInput = deleteOneSection.querySelector("input") as HTMLInputElement;

  deleteOneInput.addEventListener("input", () => {
    deleteOneInput.value.length > 0
      ? deleteOneButton.removeAttribute("disabled")
      : deleteOneButton.setAttribute("disabled", "");
  });

  deleteOneButton.addEventListener("click", async (e) => {
    e.preventDefault();
    console.log("Deleting data...");
    const id = deleteOneInput.value;

    console.log(id);

    if (!id) {
      insertText(outputSection, "L'id est obligatoire", 0);
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
    const json: APIResponse = await response.json();

    if (json.error) insertText(outputSection, json.error, response.status, { error: true });
    if (json.message) insertText(outputSection, json.message, response.status);
    if (json.data) insertData(outputSection, json.data);
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
  const createOneButton = createOneSection.querySelector("button") as HTMLButtonElement;
  const outputSection = createOneSection.nextElementSibling as HTMLElement;
  const createOneInput = createOneSection.querySelector("input[name='name']") as HTMLInputElement;
  const createOneInputDescription = createOneSection.querySelector("input[name='description']") as HTMLInputElement;
  const createOneInputPrice = createOneSection.querySelector("input[name='price']") as HTMLInputElement;
};
/**
 * Run the app
 */
const run = () => {
  setupReadAll();
  setupReadOne();
  setupDelete();
};

run();
