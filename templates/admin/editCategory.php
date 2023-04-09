<?php include "templates/include/header.php"; //SESSION START ?>
      <h1><?=  $results['pageTitle']?></h1>

      <form action="admin.php?action=<?=  $results['formAction']?>" method="post">
        <input type="hidden" name="categoryId" value="<?=  $results['category']->id ?>"/>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
<?php } ?>

<div class="">
            <label for="name">Názov novej kategórie</label>
            <input type="text" name="name" id="name" placeholder="napr. Polievky." required autofocus maxlength="255" value="<?=  htmlspecialchars( $results['category']->name )?>" />
 

            <label for="description">Popis kategórie</label>
            <input type="text" name="description" id="description" placeholder="Krátky popis o čom je kategória."  maxlength="255" value="<?=  htmlspecialchars( $results['category']->description )?>" />
          </li>
          </div>

        <div class="buttons">
          <input class="btn btn-small" type="submit" name="saveChanges" value="Uložiť" />
        </div>

      </form>

<?php if ( $results['category']->id ) { ?>
      <p><a href="admin.php?action=deleteCategory&amp;categoryId=<?=  $results['category']->id ?>" onclick="return confirm('Delete This Category?')">Delete This Category</a></p>
<?php } ?>

<?php include "templates/include/footer.php" ?>