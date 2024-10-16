import {
  API_CREATE_ONE_ENDPOINT,
  API_DELETE_ONE_ENDPOINT,
  API_READ_ALL_ENDPOINT,
  API_READ_ONE_ENDPOINT,
} from "./const.js";
import type { APIResponse, ClientData } from "./types";

export const fetchReadOne = async (clientData: ClientData["READONE"]) => {
  const { id } = clientData;
  const response = await fetch(`${API_READ_ONE_ENDPOINT}?id=${id}`);
  const json: APIResponse["READ"] = await response.json();
  return [json, response] as const;
};

export const fetchReadAll = async () => {
  const response = await fetch(API_READ_ALL_ENDPOINT);
  const json: APIResponse["READ"] = await response.json();
  return [json, response] as const;
};

export const fetchDeleteOne = async (clientData: ClientData["DELETE"]) => {
  const fetchOptions = {
    method: "DELETE",
    body: JSON.stringify(clientData),
    headers: {
      "Content-Type": "application/json",
    },
  };

  const response = await fetch(API_DELETE_ONE_ENDPOINT, fetchOptions);
  const json: APIResponse["DELETE"] = await response.json();
  return [json, response] as const;
};

export const fetchCreateOne = async (clientData: ClientData["CREATE"]) => {
  const fetchOptions = {
    method: "POST",
    body: JSON.stringify(clientData),
    headers: {
      "Content-Type": "application/json",
    },
  };

  const response = await fetch(API_CREATE_ONE_ENDPOINT, fetchOptions);
  const json: APIResponse["CREATE"] = await response.json();
  return [json, response] as const;
};
