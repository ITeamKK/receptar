<?php /*Tato stranka je na zobrazenie KONKRETNYCH RECEPTOV. Presmerovana z viewArticles.php */ ?>
<?php include "templates/include/header.php"; //SESSION START
?>
<?php include "templates/include/searchBox.php" ?>
<?php include "templates/admin/include/backto.php" ?>

<?php
//d($results);
//d($article);

//d($objAuthor, $authorName, $_SESSION);
?>
<section class="light_border">

      <!-- PREVIOUS ARTICLE -->
      <div class="d-flex align-items-center justify-content-between">
            <?php
            if (is_numeric($results['previousArticle'])) { ?>
                  <p><a class="tooltip" href="./?action=viewArticle&amp;articleId=<?= $results['previousArticle'] ?>">
                              <span class="tooltiptext">Previous recipe</span>
                              <span style="font-size: 3em;">
                                    <i class="fas fa-arrow-left"></i>
                              </span>

                        </a></p>
            <?php
            } else { ?>
                  <span style="font-size: 3em;visibility:hidden;">
                        <i class="fas fa-arrow-left"></i>
                  </span>
            <?php } ?>

            <?php //ARTICLE TITLE
            ?>
            <h1 class="flex-1 article-title"><?= htmlspecialchars($results['article']->title) ?></h1>

            <!-- NEXT ARTICLE -->

            <?php if (is_numeric($results['nextArticle'])) { ?>
                  <p><a class="tooltip" href="./?action=viewArticle&amp;articleId=<?= $results['nextArticle'] ?>">
                              <span class="tooltiptext">Nasledujúci recept</span>
                              <span style="font-size: 3em;">
                                    <i class="fas fa-arrow-right"></i>
                              </span>

                        </a></p>
            <?php
            } else { ?>
                  <span style="font-size: 3em;visibility:hidden;">
                        <i class="fas fa-arrow-right"></i>
                  </span>
            <?php } ?>

            <?php /*/ ?> if (isset($_SESSION['username'])) {
                  //if user is logged in, there will be option to change data in article
            ?>
                  <div><a class="btn neomorf_2_inner p-1" href="admin.php?action=editArticle&amp;articleId=<?=  $results['article']->id ?>">Upraviť recept</a>
                  </div>
            <?php } ?>

             <?php /*/ ?>
      </div>

      <!-- <i data-feather="circle"></i> -->
      <div class="d-flex justify-content-end p-5">
            <?php //FAVOURITES HEART
            include "templates/admin/include/favourites.php"; ?>

            <?php  //MODIFICATION - EDITING BUTTONS
            if (isset($_SESSION['username'])) {
                  if (($_SESSION['username'] == $authorName) || (checkAdmin())) {
                        include "templates/admin/include/modifications.php";
                  }
            } ?>
      </div>
      <?php
      //CATEGORY INFORMATION
      ?>
      <div class="d-flex justify-content-between">
            <h4 class="pl-3 text-left backToCategoryRecipes"> Kategória: <?php if ($results['category']) { ?> </h4>
            <p class="p-3 halfSize text-left article-description"> <a href="./?action=viewCategory&amp;idCategory=<?= $results['category']->id ?>"><?= htmlspecialchars($results['category']->name) ?></a> </p>
      <?php } ?>



      </div>
      <hr>

      <div class="d-flex justify-content-between">
            <?php
            //HALF SIZED DESCRIPTION(summary in db)
            ?>
            <h4 class="p-3 text-left">Poznámka: </h4>
            <p class="p-3 halfSize text-left article-description">
                  <?= htmlspecialchars($results['article']->summary) ?>
            </p>

            <?php
            //HALF SIZED IMAGE
            ?>
            <?php
            if ($imagePath = $results['article']->getImagePath()) {
                  // d($imagePath);
            ?>
                  <img class="pr-3 halfSize" src="<?= $imagePath ?>" alt="Article Image" />
            <?php } ?>
      </div>

      <hr>

      <div class="d-flex flex-column justify-content-between">
            <?php
            //MAIN BODY OF RECIPE
            ?>
            <h4 class="text-left article-main-header">Recept: </h4>
            <div>
                  <p class="text-left article-main"><?= $results['article']->content ?></p>
            </div>
            <hr>

            <h4 class="text-left article-main-header">Kalórie : </h4>
            <p class="text-left article-main">
                  <?= $results['article']->additional ?>
            </p>

            <p class=" p-3 text-right">Pridané : <?= date('j F Y', $results['article']->publicationDate) ?>
                  <span> - užívateľom : <?= $results['article']->author ?></span>
                  <?php //d($results['article']);
                  ?>
            <div class="userInfoContainer text-center">
                  <?php if ($userImagePath) {
                        // d($userImagePath,$userInfo,$objAuthor)
                  ?>
                        <img class="author-obrazok" src="<?= $userImagePath ?>" alt="User Image" />
                        <p class=""><?= $objAuthor->aboutUser ?></p>
                  <?php } ?>
            </div>
            </p>


      </div>
      </div>

</section>

<!-- <script>
  feather.replace()
</script> -->

<?php include "templates/include/footer.php" ?>