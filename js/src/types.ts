// Types
// --

type BasicAPIResponse = {
  error: string | null;
  message: string | null;
};
export type APIResponse = {
  CREATE: {
    data: Record<"id", string>[];
  } & BasicAPIResponse;
  READ: {
    data: Product[];
  } & BasicAPIResponse;
  DELETE: {
    data: never[];
  } & BasicAPIResponse;
  UPDATE: {
    data: Record<"id", string>[];
  } & BasicAPIResponse;
};

export type ClientData = {
  CREATE: {
    name: string;
    description: string;
    price: string;
  };
  DELETE: {
    id: string;
  };
  UPDATE: Omit<Product, "date_creation">;
  READALL: never;
  READONE: {
    id: string;
  };
};

export type Product = {
  id: string;
  name: string;
  description: string;
  price: string;
  date_creation: string;
};

export type InsertTextOptions = {
  ctn: HTMLElement;
  text: string;
  code?: number | null;
  error?: boolean;
  classList?: string;
};
