'use strict'
/* in HTML onclick="alertTest(this)" */
/* function alertText (id) {
  id.innerHTML = 'shit'
} */

/* CONSTANTS */
const passwordInput = document.getElementById('password')
const confirmPassword = document.getElementById('password_repeat')
const submitButton = document.getElementById('submitRegister')
const errorMsg = document.getElementById('error-message')

/* EVENT LISTENERS */
passwordInput.addEventListener('input', ($event) => {
  if ($event.target.value.length >= 6 && $event.target.value.length <= 20) {
    submitButton.removeAttribute('disabled')
    submitButton.classList.remove('disabled')
    submitButton.classList.add('logRegBtn')
  } else {
    submitButton.setAttribute('disabled', 'true')
    submitButton.classList.add('disabled')
    submitButton.classList.remove('logRegBtn')
  }
})

confirmPassword.addEventListener('blur', () => {
  if (passwordInput.value === confirmPassword.value) {
    passwordInput.style.border = 'thin solid green'
    confirmPassword.style.border = 'thin solid green'
    errorMsg.style.display = 'none'
  } else {
    passwordInput.style.border = 'thin solid red'
    confirmPassword.style.border = 'thin solid red'
    errorMsg.style.display = 'inline'
  }
})

// function redirectPage(href) {
//   window.location.replace(href) //href="https://www.w3schools.com"
// }


function showText () {
  var passField = document.getElementById('password')
  var passFieldRepeat = document.getElementById('password_repeat')
  if (passField.type === 'password') {
    passField.type = 'text'
    passFieldRepeat.type = 'text'
  } else {
    passField.type = 'password'
    passFieldRepeat.type = 'password'
  }
}
