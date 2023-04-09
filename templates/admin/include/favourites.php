<?php
/*if User is logged in, there will be more options,
 -like button(heart)
*/
if (isset($_SESSION['username'])) {
  //d($results,$article);

  //when we want to see clickable heart
  if ($article != null) { ?>

    <div class="likeButton">
      <a class="tooltip" href="javascript:;" onclick="addArticleToFavourites('<?=  $article->id ?>');updateFavourites('<?=  $article->id ?>');">
        <div class="favHeartTooltipText">Pridať do obľúbených</div>
        <i id="iconFavourite-<?=  $article->id ?>" class="<?php
          //get array from DB string -> array/false
          if($array = User::getFavouritesByUsername($_SESSION['username'])){
            //if there is article in db, show SOLID icon
            //array_search() vrati true/false!!
            if(array_search(($article->id), $array) !== false){
              echo 'fas ';

            //if there is NO such article in db, show REGULAR icon
            }else{
              echo 'far ';
            }
          //there are no articles in favouriteArticles(db)
          }else{
            echo 'far';
          }
          ?> fa-heart p-1"></i>
      </a>
    </div>

  <?php
  //when we want to modify article cards in category
  }else {
  }
  ?>
<?php
}
?>

<?php /*/ ?>
//JAVASCRIPT AJAX -> adds/removes article ID from DB and changes color of icon
<?php /*/ ?>
<script src="./././assets/js/modificationsAjax.js?v=<?=  time(); ?>"></script>



