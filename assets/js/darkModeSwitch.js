"use strict";

const mode = window.localStorage.getItem("mode");

if(mode){
  if (currentTheme === "dark") {
    toggleSwitch.checked = true;
  }
}

window.localStorage.setItem("mode", "light");
const modeBtn = document.getElementById("darkModeSwitch");

modeBtn.onclick = (e) => {
  
  if (mode === "light") {
    document.documentElement.classList.remove("light");
    document.documentElement.classList.add("dark");
    modeBtn.innerHTML = '<i class="fas fa-sun"></i>';
    window.localStorage.setItem("mode", "dark");
  } else {
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    modeBtn.innerHTML = '<i class="fas fa-moon"></i>';
    window.localStorage.setItem("mode", "light");
  }
};

const toggleSwitch = document.querySelector(
  '.theme-switch input[type="checkbox"]'
);
const currentTheme = localStorage.getItem("theme");

if (currentTheme) {
  document.documentElement.setAttribute("data-theme", currentTheme);

  if (currentTheme === "dark") {
    toggleSwitch.checked = true;
  }
}

function switchTheme(event) {
  if (event.target.checked) {
    document.documentElement.setAttribute("data-theme", "dark");
    localStorage.setItem("theme", "dark");
  } else {
    document.documentElement.setAttribute("data-theme", "light");
    localStorage.setItem("theme", "light");
  }
}

toggleSwitch.addEventListener("change", switchTheme, false);