'use strict'

/* CONSTANTS */
// const errorMsg = document.getElementById('error-message')
// const loginUsername = document.getElementById('loginUsername')
const loginPassword = document.getElementById('loginPassword')
const button = document.getElementById('submitLogin')
const resendDiv = document.getElementById('resendDiv')
const loginName = document.getElementById('loginUsername')
// const footer = document.getElementsByTagName('footer')
const loginNameReset = document.getElementById('resetPasswordName')
const loginEmailReset = document.getElementById('inputEmail')
const reginstrationUsername = document.getElementById('username')
const registrationPassword = document.getElementById('password')
const registrationPasswordRepeated = document.getElementById('password_repeat')
const registrationEmail = document.getElementById('email')


/* EVENT LISTENERS */
loginPassword.addEventListener('input', ($event) => {
  //Reads if the input fields to log in are filled and unblocks login button
  if ($event.target.value.length > 0) {
    button.removeAttribute('disabled')
    button.classList.remove('disabled')
    button.classList.add('logRegBtn')
  } else {
    button.setAttribute('disabled', 'true')
    button.classList.add('disabled')
    button.classList.remove('logRegBtn')
  }
})


/* FUNCTIONS */
// function checkFieldsForLogin () {
//   button.innerHTML = 'shit x 2'
//   if (loginUsername.value.length > 0) {
//     button.removeAttribute('disabled')
//     button.classList.remove('disabled')
//   }
// }

// function redirectPage(href) {
//   window.location.replace(href) //href="https://www.w3schools.com"
// }

//called directly from HTML
//modifies password field so that characters can be read
function showText () {
  var passField = document.getElementById('loginPassword')
  if (passField.type === 'password') {
    passField.type = 'text'
  } else {
    passField.type = 'password'
  }
}

//called directly from HTML
//appear form to reset password
function showEmailForm () {
  document.querySelector('#loginForm').classList.add('d-none');
  resendDiv.classList.remove('resendPass')
}