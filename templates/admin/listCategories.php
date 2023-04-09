<?php include "templates/include/header.php"; //SESSION START ?>
      <h1><?=$results['pageTitle']?></h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?=  $results['statusMessage'] ?></div>
<?php } ?>

      <table class="d-flex justify-content-center">

<?php foreach ( $results['categories'] as $category ) { ?>

        <tr onclick="location='admin.php?action=editCategory&amp;categoryId=<?=  $category->id?>'">
          <td>
            <?=  $category->name?>
          </td>
        </tr>

<?php } ?>

      </table>

      <p><a href="admin.php?action=newCategory">Pridať novú <i class="fas fa-plus-square" aria-hidden="true"></i></a></p>

      <p>Total: <?= $results['totalRows'] ?> </p>

<?php include "templates/include/footer.php" ?>