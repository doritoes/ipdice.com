const triggerObject = document.getElementById("dice-text"); 
const triggerLink = document.getElemementById('image');

function switchTheme() {
  const themeLink = document.getElementById("theme-stylesheet");
  const currentTheme = themeLink.getAttribute("href");
  if (currentTheme === "/static/styles/main.css") {
    themeLink.setAttribute("href", "/static/styles/dark.css"); 
  } else {
    themeLink.setAttribute("href", "/static/styles/main.css");
  }
}

function switchPage() {
  window.location.href = '/static/pages/details.php';
}

triggerObject.addEventListener("click", switchTheme);
triggerLink.addEventListener("click", switchPage);
