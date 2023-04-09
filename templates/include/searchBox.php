<div id="searchBox" class="searchBox p-3">

  <form class="searchForm" action="index.php" method="GET">
    <input type="hidden" id="action" name="action" value="searchForArticle">
    <input class="searchInputBar" type="text" id="q" value="" name="q" pattern=".{4,}" placeholder="Hľadať recept" onfocus="this.placeholder = ''">

    <input readonly style="font-family: FontAwesome" value="&#xf002;" class="btn btnSearch" type="submit">

  </form>

  <!--  <button type="button" class="close">x</button> -->
</div>

<div class=" p-3">
  <p><a href="./?action=archive">Zobraziť všetky recepty (<?= $results['totalRows'] ?>)</a></p>
</div>