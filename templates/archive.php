<?php include "templates/include/header.php"; ?>
<?php include "templates/include/searchBox.php" ?>

<section>
  <h1><a href="./?action=archive"><?=  htmlspecialchars($results['pageHeading']) ?> (<?=  $results['totalRows'] ?>) </a></h1>

  <section id="headlines" class="card-container">
    <!--VYTVORIME VSETKY ARTICLES NA STRANKE HOMEPAGE-->
    <?php
    foreach ($results['articles'] as $article) { ?>

      <article class="category-card">
        <?php if ($imagePath = $article->getImagePath(IMG_TYPE_THUMB)) { ?>
          <!--ak existuje obrazok tak vrati obrazok a summary=popis pod obrazkom aj existuje bude klikatelny -->
          <a href=".?action=viewArticle&amp;articleId=<?=  $article->id ?>"><img class="articleImageThumb card-img-top" src="<?=  $imagePath ?>" alt="Article Thumbnail" /></a>
        <?php } ?>

        <div class="card-body">
          <h2 class="card-title">
            <!--vyprodujujeme link na vsetky article z DB -->
            <a href=".?action=viewArticle&amp;articleId=<?=  $article->id ?>"><?=  htmlspecialchars($article->title) ?></a>
          </h2>
          <p class="summary card-text">

            <?=  htmlspecialchars($article->summary) ?>
          </p>
        </div>


      </article>
    <?php } ?>
  </section>

  <p>Celkovo: <?=  $results['totalRows'] ?> <?=  ($results['totalRows'] != 1) ? 'receptov' : 'recept' ?>.</p>

  </div>
</section>

<?php include "templates/include/footer.php" ?>