//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |              THIS FILE CONTAINS ALL DOM                     |
// |              RELATED FUNCTIONS AND VARIABLES                |
// |_____________________________________________________________|
//
/**
 * DOM ELEMENTS
 * --
 */
// We find the section by its data-endpoint attribute (read, read-one, create, update, delete)
// Those section will be used to insert data, display tables, display errors, handle events, form,  etc.
const findSection = (sectionName) => {
    return inputSections.find((section) => section.dataset.endpoint === sectionName);
};
// all sections
const inputSections = Array.from(document.querySelectorAll("[data-endpoint]")), selectedIdSection = document.querySelector("[data-output='id']"), readSection = findSection("read"), readOneSection = findSection("read-one"), createSection = findSection("create"), updateSection = findSection("update"), deleteSection = findSection("delete");
/**
 * We export DOM elements as a huge object store in a constant where all elements are grouped by their section
 */
export const dom = {
    read: {
        section: readSection,
        btn: readSection.querySelector("button"),
        output: readSection.nextElementSibling,
    },
    readOne: {
        section: readOneSection,
        btn: readOneSection.querySelector("button"),
        output: readOneSection.nextElementSibling,
        input: readOneSection.querySelector("input"),
    },
    create: {
        section: createSection,
        btn: createSection.querySelector("button"),
        output: createSection.nextElementSibling,
        inputs: Array.from(createSection.querySelectorAll("input")),
    },
    update: {
        section: updateSection,
        btn: updateSection.querySelector("button"),
        output: updateSection.nextElementSibling,
        inputs: Array.from(updateSection.querySelectorAll("input")),
    },
    deleteOne: {
        section: deleteSection,
        btn: deleteSection.querySelector("button"),
        output: deleteSection.nextElementSibling,
        input: deleteSection.querySelector("input"),
    },
    selectedId: {
        section: selectedIdSection,
        output: selectedIdSection.querySelector("output"),
    },
};
// We extract all the elements grouped by their section ( here delete, readOne)
const { deleteOne, readOne, create } = dom;
// Data submissions are disabled if the input is empty
// This function toggles the disabled attribute if input has a value
const toggleDisabled = (btn, input) => {
    input.value.length > 0 ? btn.removeAttribute("disabled") : btn.setAttribute("disabled", "");
};
// in delete and readOne sections, if the input has a value, we can submit with the button and send the request
deleteOne.input.addEventListener("input", () => toggleDisabled(deleteOne.btn, deleteOne.input));
readOne.input.addEventListener("input", () => toggleDisabled(readOne.btn, readOne.input));
// in create section, we check if all inputs have a value to enable the submit button
create.inputs.forEach((input) => {
    input.addEventListener("input", () => {
        const isFormValid = create.inputs.every((input) => input.value.length > 0);
        isFormValid ? create.btn.removeAttribute("disabled") : create.btn.setAttribute("disabled", "");
    });
});
/**
 * it is a vanilla and simple <FormControl> React like component
 * **/
export const insertText = ({ ctn, text, code = null, error = false, classList = "" }) => {
    const prefix = error ? "[ERROR] : " : "";
    const suffix = code ? `-- Code ${code}` : "";
    const className = error ? "pico-color-red-500" : "";
    ctn.innerHTML = `
          <div>
              <p class="${className + " " + classList}">${prefix} ${text} ${suffix}</p>
          </div>
      `;
};
/**
 *,insert a table to display the products given data and a container
 *
 * @param ctn The container where the data will be inserted
 * @param data The data to insert
 *
 */
export const insertTable = (ctn, data) => {
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
            td.textContent = `${value}`;
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
export const insertUpdateFields = (data) => {
    dom.update.inputs.forEach((input) => {
        const value = data[input.name];
        input.value = `${value}`;
    });
};
export const insertReadOneFields = (data) => {
    dom.readOne.input.value = data.id;
};
