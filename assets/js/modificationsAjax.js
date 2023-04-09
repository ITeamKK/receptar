'use strict'

// This toggles heart icon on click
function addArticleToFavourites (articleId) {
  const string = 'iconFavourite-'
  const iconIdName = string + articleId
  const favouriteArticle = document.getElementById(iconIdName)
  // class="iconFavourite-20"
  // Odoberieme 'far' a naopak pridame 'fas'
  if (favouriteArticle.classList.contains('far')) {
    favouriteArticle.classList.remove('far')
    favouriteArticle.classList.add('fas')
    // alertify.log("Pridal si recept do obľúbených");
  } else if (favouriteArticle.classList.contains('fas')) {
    favouriteArticle.classList.remove('fas')
    favouriteArticle.classList.add('far')
  }
  /* fas => solid, far => regular */
}

// This on click sends information to PHP code to be evaluated and RESPONSE updates eventually the class of heart icon
function updateFavourites (articleId) {
  // Jquery was -> $('#favoritesAjaxBox').load('/plugins/store/public/models/addToFavorites.php?prID=' + product);
  var xhttp
  xhttp = new XMLHttpRequest()
  xhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      // console.log(this.response)
      // we obtain responseText from xhttp
      var updatedIcon = JSON.parse(this.response)

      // updates header heart icon
      var headerIcon = document.getElementById('iconFavouriteHeader')
      // document.getElementById('iconFavouriteHeader').classList.toggle(updatedIcon)
      if (headerIcon.classList.contains('far')) {
        headerIcon.classList.remove('far')
        headerIcon.classList.add(updatedIcon)
      } else if (headerIcon.classList.contains('fas')) {
        headerIcon.classList.remove('fas')
        headerIcon.classList.add(updatedIcon)
      }
    }
  }
  xhttp.open('GET', '././templates/admin/include/addToFavourites.php?articleID=' + articleId, true)
  xhttp.send()
}
