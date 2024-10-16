import { fetchCreateOne, fetchDeleteOne, fetchReadAll, fetchReadOne } from "./APIFetch.js";
import { dom, insertTable, insertText } from "./dom.js";
import { save } from "./storage.js";
import { setupSyncId } from "./syncId.js";
import { themeLogic } from "./theme-toggle.js";
console.log("Starting the app...");
/**
 *
 * Setup the read all logic
 *
 */
const readAll = () => {
    console.log("Setting up read all logic...");
    const { btn, output } = dom.read;
    const handleMouseDown = async () => {
        console.log("Fetching data...");
        const [json, response] = await fetchReadAll();
        if (json.error)
            insertText({ ctn: output, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: output, text: json.message, code: response.status });
        if (json.data)
            (output.innerHTML = ""), insertTable(output, json.data);
    };
    btn.addEventListener("mousedown", handleMouseDown);
};
/**
 *
 * Setup the read one logic
 *
 */
const readOne = () => {
    console.log("Setting up read one logic...");
    const { btn, input, output, section } = dom.readOne;
    btn.addEventListener("mousedown", async (e) => {
        e.preventDefault();
        console.log("Fetching data...");
        const id = input.value;
        if (!id) {
            insertText({ ctn: output, text: "L'id est obligatoire" });
            return;
        }
        const [json, response] = await fetchReadOne({ id });
        if (json.error)
            insertText({ ctn: output, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: output, text: json.message, code: response.status });
        if (json.data)
            insertTable(output, json.data);
        save("selected-id", id);
    });
};
/**
 *
 * Setup the delete logic
 *
 */
const deleteOne = () => {
    console.log("Setting up delete logic...");
    const { btn, input, output } = dom.deleteOne;
    btn.addEventListener("mousedown", async (e) => {
        e.preventDefault();
        console.log("Deleting data...");
        const id = input.value;
        if (!id) {
            insertText({ ctn: output, text: "L'id est obligatoire" });
            return;
        }
        const clientData = { id };
        const [json, response] = await fetchDeleteOne(clientData);
        if (json.error)
            insertText({ ctn: output, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: output, text: json.message, code: response.status });
        if (json.data)
            insertTable(output, json.data);
    });
};
/**
 *
 * Setup the create logic
 *
 */
const createOne = () => {
    console.log("Setting up create logic...");
    const { btn, inputs, output } = dom.create;
    btn.addEventListener("mousedown", async (e) => {
        e.preventDefault();
        console.log("Creating data...");
        const clientData = inputs.reduce((acc, input) => {
            acc[input.name] = input.value;
            return acc;
        }, {});
        const [json, response] = await fetchCreateOne(clientData);
        if (json.error)
            insertText({ ctn: output, text: json.error, code: response.status, error: true });
        if (json.message)
            insertText({ ctn: output, text: json.message, code: response.status });
        if (json.data)
            output.innerHTML += `<p>Le produit a été créé avec l'id : ${json.data[0].id}</p>`;
        save("selected-id", json.data[0].id);
    });
};
/**
 *
 * Run the app
 *
 */
const run = () => {
    themeLogic();
    readAll();
    readOne();
    deleteOne();
    createOne();
    setupSyncId();
};
run();
