<?php include "templates/include/header.php"; //SESSION START ?>

<?php if (isset($results['errorMessage'])) { ?>
  <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
<?php } ?>

<?php //If not existing Category Name- may be were showing search results, wont display H1
//d($results,$articlesCount);
if(!empty($results['categoryName'])){ ?>
  <h1> <?= $results['pageTitle'] . " " . $results['categoryName']?></h1>
  <h2> <?=  ' Podkategórie ' ?></h2>
<?php }else{ ?>
  <h2> <?= $results['pageTitle']?></h2>
  <h4>Počet zodpovedajúcich výsledkov : <?= $articlesCount ?> </h4>
<?php } ?>

<section  class="card-container">
  <?php
  //d($results,$_SESSION);
    if(!empty($results['articles'])){
      foreach($results['articles'] as $article){
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
        <?php } else{ ?>
          <h2 class="m-0 articleTitle">
            <?=  htmlspecialchars($article->title) ?>
          </h2>
          <?php } ?>

          <p class="summary"><?=  htmlspecialchars($article->summary) ?></p>
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
    }else {
      ?>
      <p> Nenasli sa ziadne recepty. </p>
    <?php }?>

</section>


<?php include "templates/include/footer.php" ?>