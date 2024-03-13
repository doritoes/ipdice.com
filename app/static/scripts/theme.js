const triggerObject = document.getElementById("dice-text"); 

function switchTheme() {
  const themeLink = document.getElementById("theme-stylesheet");
  const currentTheme = themeLink.getAttribute("href");

  if (currentTheme === "/static/styles/main.css") {
    themeLink.setAttribute("href", "/static/styles/dark.css"); 
  } else {
    themeLink.setAttribute("href", "/static/styles/main.css");
  }
}

// Add an event listener to the image
triggerObject.addEventListener("click", switchTheme);
