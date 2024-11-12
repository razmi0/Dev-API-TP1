//  _____ _______ _______ _______ _______ _______ _______ _______
// |                                                             |
// |              THIS FILE CONTAINS ALL DOM                     |
// |              RELATED FUNCTIONS AND VARIABLES                |
// |_____________________________________________________________|
//

import type { InsertTextOptions, Product } from "../types";

/**
 * DOM ELEMENTS
 * --
 */

// We find the section by its data-endpoint attribute (read, read-one, create, update, delete)
// Those section will be used to insert data, display tables, display errors, handle events, form,  etc.
const findSection = (sectionName: string) => {
  return inputSections.find((section) => section.dataset.endpoint === sectionName);
};

// all sections
const inputSections = Array.from(document.querySelectorAll("[data-endpoint]")) as HTMLElement[],
  readSection = findSection("read"),
  readOneSection = findSection("read-one"),
  createSection = findSection("create"),
  updateSection = findSection("update"),
  deleteSection = findSection("delete");

/**
 * We export DOM elements as a huge object store in a constant where all elements are grouped by their section
 */
export const dom = {
  read: {
    section: readSection,
    btn: readSection.querySelector("button"),
    output: readSection.nextElementSibling as HTMLElement,
  },
  readOne: {
    section: readOneSection,
    btn: readOneSection.querySelector("button"),
    output: readOneSection.nextElementSibling as HTMLElement,
    input: readOneSection.querySelector("input") as HTMLInputElement,
  },
  create: {
    section: createSection,
    btn: createSection.querySelector("button"),
    output: createSection.nextElementSibling as HTMLElement,
    outputError: createSection.nextElementSibling.querySelector("#error") as HTMLDivElement,
    outputMessage: createSection.nextElementSibling.querySelector("#message") as HTMLDivElement,
    outputData: createSection.nextElementSibling.querySelector("#error_data") as HTMLDivElement,
    inputs: Array.from(createSection.querySelectorAll("input")) as HTMLInputElement[],
  },
  update: {
    section: updateSection,
    btn: updateSection.querySelector("button#submitUpdate") as HTMLButtonElement,
    output: updateSection.nextElementSibling as HTMLElement,
    inputs: Array.from(updateSection.querySelectorAll("input")) as HTMLInputElement[],
    idsCtn: updateSection.querySelector("[data-ids]") as HTMLElement,
  },
  deleteOne: {
    section: deleteSection,
    btn: deleteSection.querySelector("button"),
    output: deleteSection.nextElementSibling as HTMLElement,
    outputError: deleteSection.nextElementSibling.querySelector("#error") as HTMLDivElement,
    outputData: deleteSection.nextElementSibling.querySelector("#error_data") as HTMLDivElement,
    outputMessage: deleteSection.nextElementSibling.querySelector("#message") as HTMLDivElement,
    input: deleteSection.querySelector("input") as HTMLInputElement,
  },
} as const;

// We extract all the elements grouped by their section ( here delete, readOne)
const { deleteOne, readOne, create } = dom;

// Data submissions are disabled if the input is empty
// This function toggles the disabled attribute if input has a value
const toggleDisabled = (btn: HTMLButtonElement, input: HTMLInputElement) => {
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
export const insertText = ({ ctn, text, code = null, error = false, classList = "" }: InsertTextOptions) => {
  console.log(text, error);
  const prefix = error ? "[ERROR] : " : "";
  const suffix = error && code ? `-- Code ${code}` : "";
  const className = error ? "pico-color-red-500" : "";
  ctn.innerHTML = `
          <div>
              <article class="${className + " " + classList}">${prefix} ${text} ${suffix}</article>
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
export const insertTable = (ctn: HTMLElement, data: Product[]) => {
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

export const insertIdsUpdate = (ctn: HTMLElement, ids: string[]): HTMLButtonElement[] => {
  const buttons = ids.map((id) => {
    const button = document.createElement("button");
    button.textContent = id;
    button.dataset.updateIds = id;
    button.classList.add("secondary", "outline");
    return button;
  });

  ctn.append(...buttons);

  return buttons;
};
