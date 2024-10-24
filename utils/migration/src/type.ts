export type OriginalProduct = {
  id: number;
  title: string;
  price: number;
  description: string;
  category: string;
  image: string;
  rating: {
    rate: number;
    count: number;
  };
};

export type TargetProduct = {
  name: string;
  description: string;
  prix: number;
};
