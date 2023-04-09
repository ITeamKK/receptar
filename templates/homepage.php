<?php include "templates/include/header.php"; //SESSION START ?>
<?php include "templates/include/searchBox.php" ?>
<?php include "templates/include/quoteBox.php" ?>

<?php
/*<h1><?=  htmlspecialchars($results['pageTitle']) ?></h1>*/

//d($results,$_SESSION);
//d($mail);
// d($USERNAME,$USERNAME2,$usernameFormEntry);
?>

<p><a href="./?action=archive">Zobraziť všetky recepty (<?= $results['totalRows'] ?>)</a></p>
<section class="card-container mt-3">
  <?php foreach ($results['categories'] as $category) {
    //d($results['categories']);

    //If Category item HAS an Article, it will be shown.
    if (!empty(Article::getNumOfArticleByCat($category->id))) {
  ?>
      <article class="category-card">
        <?php //Whole Category card is a link
        ?>
        <?php /**/ ?>
        <a href=".?action=viewCategory&amp;idCategory=<?=  $category->id ?>">

          <?php
          // if the category has articles, get first article and show its photo
          // get the first data from array of articles
          $firstArticle = Article::getArticleByCat($category->id)['results'][0];
          //d($firstArticle);

          if ($imagePath = $firstArticle->getImagePath(IMG_TYPE_THUMB)) {
            //IMG of Category Card
          ?>
            <div class="cardImg d-flex align-items-center" style='background-image: url("<?=  $imagePath ?>")'>
            <h2 class="m-0 categoryTitle">
            <?=  htmlspecialchars($category->name) ?>
          </h2>
            </div>

          <?php } else{ ?>
          <h2 class="m-0 categoryTitle">
            <?=  htmlspecialchars($category->name) ?>
          </h2>
          <?php } ?>
          <?php /*  */ ?>
        </a>
        <?php
        $article = null;
        //d($results,$article);
        //if logged in, there will be shown more options
        include "templates/admin/include/modifications.php";
        ?>
      </article>
  <?php
    }
  } ?>


</section>


<?php include "templates/include/footer.php" ?>