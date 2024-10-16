export const save = (key: string, value: any) => {
  localStorage.setItem(key, JSON.stringify(value));
};

export const load = (key: string) => {
  const value = localStorage.getItem(key);
  return value ? JSON.parse(value) : null;
};
