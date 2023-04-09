'use strict'
//THIS FILE IS USED WHEN WE WANT TO HIDE FOOTER - e.g. ON SMALL SCREENS
//IT ADDS/REMOVES CLASS WHICH IS USED TO DISPLAY OR NOT THE FOOTER
//ALL ELEMENTS THAT ON EVENT WE WANT TO HIDE FOOTER NEED TO HAVE CLASS "hideFooterOnMobile"

/* CONSTANTS */
const allElementsWithClassHideFooter = document.getElementsByClassName('hideFooterOnMobile') //all elements with this class would have the same eventlistener?
const footer = document.getElementsByTagName('footer')

//console.log(allElementsWithClassHideFooter)

/* EVENT LISTENERS */
for (let element of allElementsWithClassHideFooter){
    //console.log(element)
    element.addEventListener('focus', function () {
        hidesFooter()
        element.scrollIntoView(false) //this focuses the element into center of the viewport
    })
    element.addEventListener('blur', function () {
        revealsFooter()
        element.scrollIntoView(false) //this focuses the element into center of the viewport)
})}
//

/* FUNCTIONS */
//hides footer but only when mediaqueries = on the mobile screens
function hidesFooter(){
    footer[0].classList.add('footer_hidden')
  }
function revealsFooter(){
    footer[0].classList.remove('footer_hidden')
}