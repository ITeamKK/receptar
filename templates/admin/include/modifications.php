<?php
/*if User is logged in, there will be more options,
 -delete
 -edit article
*/
if (isset($_SESSION['username'])) {
  //d($results,$article);

  //when we want to modify article elements
  if ($article != null) { ?>
    <div class="editingButtons">

      <a class="tooltip" href="admin.php?action=deleteArticle&amp;articleId=<?=  $article->id ?>" onclick="return confirm('Vymazať recept?')">
        <div class="tooltiptext">Vymazať recept</div>
        <i class="fas fa-times  p-1"></i>
      </a>


      <a class="tooltip" href="admin.php?action=editArticle&amp;articleId=<?=  $article->id ?>" >
        <div class="tooltiptext">Editovať recept</div>
        <i class="fas fa-edit  p-1"></i>
      </a>

    </div>

  <?php //mozno aj prompt by mohol byt s vypytanim hesla znovu pre vymazanie?


  //when we want to modify article cards in category
  } elseif ($category->id) { ?>
    <div class="editingButtons">
      <a class="tooltip" href="admin.php?action=deleteCategory&amp;categoryId=<?=  $category->id ?>" onclick="return confirm('Vymazať kategoriu?')">
        <div class="tooltiptext">Vymazať kategóriu</div><i class="fas fa-times  p-1"></i>
      </a></div>
  <?php

  //when there are no articles, there is no need to show modification buttons
  } else {
  }
  ?>
<?php
}
?>

