<?php include "templates/include/header.php"; //SESSION START 
?>

<script src="https://cdn.ckeditor.com/ckeditor5/37.0.1/classic/ckeditor.js"></script>


<script>
  // Prevents file upload hangs in Mac Safari
  // Inspired by http://airbladesoftware.com/notes/note-to-self-prevent-uploads-hanging-in-safari

  function closeKeepAlive() {
    if (/AppleWebKit|MSIE/.test(navigator.userAgent)) {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "/ping/close", false);
      xhr.send();
    }
  }
</script>

<!-- Page Title -->
<h1><?= $results['pageTitle'] ?></h1>

<section class="light_border editArticle">
  <?php

  //d($results);
  ?>

  <form class="" accept-charset="utf-8" action="admin.php?action=<?= $results['formAction'] ?>" method="post" enctype="multipart/form-data" onsubmit="closeKeepAlive()">
    <input class="" type="hidden" name="articleId" value="<?= $results['article']->id ?>" />
    <input class="" type="hidden" name="publicationDate" id="publicationDate" value="<?= $results['article']->publicationDate ? date("Y-m-d", $results['article']->publicationDate) :  date('Y-m-d') ?>" />

    <?php if (isset($results['errorMessage'])) { ?>
      <div class="errorMessage"><?= $results['errorMessage'] ?></div>
    <?php } ?>


    <?php
    //ARTICLE TITLE
    ?>
    <div>

      <label class="" for="title">Názov receptu*</label>

      <input class="" type="text" name="title" id="title" placeholder="Aké jedlo?" required autofocus maxlength="255" value="<?= htmlspecialchars($results['article']->title) ?>" />
    </div>

    <?php
    //CATEGORY INFORMATION
    ?>
    <div>
      <label class="editCategoryLabel" for="categoryId">Kategória*</label>

      <select class="editCategoryDropbox" name="categoryId" required>
        <?php foreach ($results['categories'] as $category) { ?>
          <option value="<?= $category->id ?>" <?= ($category->id == $results['article']->categoryId) ? " selected" : ""
                                                //when selected category is the same as actual-existing category, it will be marked as selected - thus -prechosen, otherwise empty 
                                                ?>>
            <?= htmlspecialchars($category->name) ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <?php
    //DESCRIPTION
    ?>
    <div>

      <label class="" for="summary">Popis receptu</label>
      <textarea class="editor" name="summary"  placeholder="Stručný popis receptu"  maxlength="1000"><?= $results['article']->summary ?></textarea>

    </div>

    <?php
    //INGREDIENTS
    ?>
    <div>

      <label class="" for="additional">Ingrediencie*</label>
      <textarea class="editor" name="additional" placeholder="Čo nám treba?" maxlength="1000"><?= $results['article']->additional ?></textarea>

    </div>


    <?php
    //INSTRUCTIONS
    ?>
    <div class="d-flex flex-column">
      <label class="" for="content">Postup*</label>
      <textarea class="editor" name="content" placeholder="Ako sa to robí?"><?= $results['article']->content ?></textarea>
    </div>

    <?php
    //AUTHOR OF THE ARTICLE --> data from admin --> newArticle()
    //d($results);
    ?>
    <div class="d-flex flex-column">
      <label class="author" for="">Autor receptu</label>
      <input class="" readonly required name="author" id="" value="<?= $results['author'] ?>" />
    </div>

    <?php
    //IMAGE UPLOAD
    //$imagePath = "";
    ?>
    <div class="d-flex flex-column">
      <label class="">Obrázok</label>
      <?php
      if ($imagePath = $results['article']->getImagePath()) {
        // d($imagePath);
      ?>
        <img class="pridany_obrazok" src="<?= $imagePath ?>" alt="Article Image" />

        <input class="" type="checkbox" name="deleteImage" id="deleteImage" value="yes" />
        <label class="" for="deleteImage">Vymazať tento obrázok</label>
      <?php } ?>

      <div class="">
        <label class="" for="image">Nahrať fotku
          <input class="custom-file-input" type="file" name="image" id="image" /></label>
      </div>
    </div>


    <?php
    //SAVE/DELETE BUTTONS
    ?>
    <div class="">

      <button class="btn btn-small p-3 m-3" type="submit" name="saveChanges">Uložiť</button>
      <?php //Ked sa zmackne ulozit zmeny ide cez Admin.php->newArticle() ak sme v newArticle alebo ide cez Admin.php->editArticle() ak sme v editArticle 
      ?>
    </div>


  </form>

  <?php //DELETE CONFIRM FUNCTION 
  ?>
  <?php if ($results['article']->id) { ?>
    <p><a href="admin.php?action=deleteArticle&amp;articleId=<?= $results['article']->id ?>" onclick="return confirm('Vymazať recept?')">Vymazať recept</a></p>
  <?php } ?>


</section>


<script>
  var allEditors = document.querySelectorAll('.editor');
  allEditors.forEach(editor => {
    ClassicEditor
      .create(editor)
      .catch(error => {
        console.error(error);
      })
  })
</script>

<?php include "templates/include/footer.php" ?>