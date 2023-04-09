<?php /*Tato stranka je na zobrazenie RECEPTOV a VYSLEDKOV VYHLADAVANIA. Presmerovana z index.php */ ?>
<?php include "templates/include/header.php"; //SESSION START
?>
<?php include "templates/include/searchBox.php" ?>

<?php 
//d($results['articles']);
?>

<?php //If not existing Category Name- may be were showing search results, wont display H1
//d($results,$articlesCount);
if (!empty($results['categoryName'])) { ?>
  <h1> <?=  $results['pageHeading'] . " " . $results['categoryName'] ?></h1>
  <?php /*/ ?>
  <h2> <?=  $results['pageSubHeading'] ?></h2>
    <?php /*/ ?>
<?php } else { ?>
  <h2> <?=  $results['pageHeading'] ?></h2>
  <h3>Počet výsledkov: <?=  $articlesCount ?> </h3>
<?php } ?>
<?php /*/ ?>
<div>
  <i href="javascript:;" onclick="sortAscending('<?=  $results['articles'] ?>');" class="sortAsc fas fa-arrow-down  p-1"></i>

  <i class="sortDesc fas fa-arrow-up  p-1"></i>
</div>
  <?php /*/ ?>
<section class="card-container">
  <?php
  // d($_SESSION);
  // d($results);
  //d($results['articles']);



  if (!empty($results['articles'])) {
    foreach ($results['articles'] as $article) {
  ?>

      <article class="article-card ">
        <a href=".?action=viewArticle&amp;articleId=<?=  $article->id ?>">


          <?php
          if ($imagePath = $article->getImagePath(IMG_TYPE_THUMB)) {
            //IMG of Category Card
          ?>
            <div class="articleCardImg" style='background-image: url("<?=  $imagePath ?>")'>
            </div>
            <h2 class="m-0 articleTitle"> <?=  htmlspecialchars($article->title) ?> </h2>

            <?php
            /* <img class="articleImageThumb" src="<?=  $imagePath ?>" alt="Category Thumbnail" />
          */
            ?>
          <?php } else { ?>
            <h2 class="m-0 articleTitle">
              <?=  htmlspecialchars($article->title) ?>
            </h2>
          <?php } ?>

          <p class="summary"><?= $article->summary ?></p>
          <span class="pubDate"><?=  date('j F', $article->publicationDate) ?></span>
        </a>
        <?php
        //if logged in, there will be shown more options
        // d($results,$article);
        include "templates/admin/include/modifications.php";
        include "templates/admin/include/favourites.php";
        ?>
      </article>
    <?php
    }
  } else {
    ?>
    <p> Nenašli sa žiadne recepty. </p>
  <?php } ?>

</section>

<script src="./././assets/js/sort.js?v=<?=  time(); ?>"></script>

<?php include "templates/include/footer.php" ?>

