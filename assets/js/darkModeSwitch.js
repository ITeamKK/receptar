"use strict";
var mode;
const modeBtn = document.getElementById("darkModeSwitch");

if (!window.localStorage.getItem("mode")) {
  mode = "light";
  window.localStorage.setItem("mode", "light");
} else {
  mode = window.localStorage.getItem("mode");
  switch (mode) {
    case "light":
      document.documentElement.classList.remove("dark");
      document.documentElement.classList.add("light");
      modeBtn.innerHTML = '<i class="fas fa-moon"></i>';
      break;
    case "dark":
      document.documentElement.classList.remove("light");
      document.documentElement.classList.add("dark");
      modeBtn.innerHTML = '<i class="fas fa-sun"></i>';
      break;
  }
}

console.log(mode);

modeBtn.onclick = (e) => {
  console.log(mode);
  if (mode === "light") {
    console.log("switch to dark");
    document.documentElement.classList.remove("light");
    document.documentElement.classList.add("dark");
    modeBtn.innerHTML = '<i class="fas fa-sun"></i>';
    window.localStorage.setItem("mode", "dark");
    mode = "dark";
  } else {
    console.log("switch to light");
    document.documentElement.classList.remove("dark");
    document.documentElement.classList.add("light");
    modeBtn.innerHTML = '<i class="fas fa-moon"></i>';
    window.localStorage.setItem("mode", "light");
    mode = "light";
  }
};
