'use strict'

function sortAscending (articles) {
  var xhttp
  xhttp = new XMLHttpRequest()
  xhttp.onreadystatechange = function () {
    if (this.readyState === 4 && this.status === 200) {
      // console.log(this.response)
      // we obtain responseText from xhttp
      // var updatedIcon = JSON.parse(this.response)
      var sortedArray = JSON.parse(this.response)
      console.log(sortedArray)
      return sortedArray
    }
  }
  xhttp.open('GET', '././templates/admin/include/sort.php?articles=' + articles, true)
  xhttp.send()
}
