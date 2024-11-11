// Types
// --

type BasicAPIResponse = {
  error: string | "";
  message: string | "";
};
export type APIResponse = {
  CREATE: {
    data: Record<"id", string>[];
  } & BasicAPIResponse;
  READ_ONE: {
    data: {
      product: Product;
    };
  } & BasicAPIResponse;
  READ_ALL: {
    data: {
      products: Product[];
    };
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
    price: number;
  };
  DELETE: {
    id: number;
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
  price: number;
  date_creation: string;
};

export type InsertTextOptions = {
  ctn: HTMLElement;
  text: string;
  code?: number | null;
  error?: boolean;
  classList?: string;
};
