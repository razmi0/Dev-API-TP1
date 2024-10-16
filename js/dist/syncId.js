import { dom } from "./dom.js";
import { load } from "./storage.js";
const { selectedId } = dom;
export const setupSyncId = () => {
    addEventListener("storage", (e) => {
        if (e.key === "selected-id") {
            console.log("Storage event detected");
            selectedId.output.textContent = e.newValue;
        }
    });
    addEventListener("DOMContentLoaded", () => {
        const selectedIdValue = load("selected-id");
        if (selectedIdValue)
            selectedId.output.textContent = selectedIdValue;
    }, { once: true });
};
