/**
 * Theme Toggle
 */
export const themeLogic = () => {
  const themeToggleButton = document.querySelector("button[data-theme]");
  if (themeToggleButton) {
    themeToggleButton.addEventListener("mousedown", () => {
      let html = document.querySelector("html[data-theme]") as HTMLElement;
      if (html) {
        let htmlTheme = html.dataset.theme;
        htmlTheme = htmlTheme === "light" ? "dark" : "light";
        html.dataset.theme = htmlTheme;
      }
    });
  }
};
