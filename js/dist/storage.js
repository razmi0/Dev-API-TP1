export const save = (key, value) => {
    localStorage.setItem(key, JSON.stringify(value));
};
export const load = (key) => {
    const value = localStorage.getItem(key);
    return value ? JSON.parse(value) : null;
};
