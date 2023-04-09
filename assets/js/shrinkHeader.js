//Changes logo when user scrolls down from the top of the page - menu will change the picture and gets smaller

/* CONSTANTS */
var lastKnownScrollPosition = 0
const logo = document.getElementById('logo')
const numberPixelsFromTop = 150;
const burgerMenu = document.getElementById('burgerMenu')
const screenWidth = 500

/* FUNCTIONS */
//Changes the logo picture
function shrinkHeader (scrollPos) {
  console.log(lastKnownScrollPosition)

  //burgerMenu.style.position = 'relative'

  logo.classList.add('logo-scrolled')
  logo.src = '/assets/images/recepty_base.png'
}

/* EVENT LISTENERS */
window.addEventListener('scroll', function (e) {
  lastKnownScrollPosition = window.scrollY  //on window scroll from the last position set to 0, there will be function calle
  if ((Math.round(lastKnownScrollPosition) >= numberPixelsFromTop) && window.innerWidth < screenWidth){
    shrinkHeader(lastKnownScrollPosition)
  } else {
   // burgerMenu.style.position = 'absolute'

    logo.classList.remove('logo-scrolled')
    logo.src = '/assets/images/logo_sk.png'
  }
})
