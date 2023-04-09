<?php
//this page contains registration form
include "templates/include/header.php"; //SESSION START ?>

<?php
//d(//$USERNAME,
  // $usernameFormEntry,
  // $formDbCheck,
  // $userStoredValues,
  // $results['user']->id,
  //$user,
  //$_POST,
  //$currentUser,
  //$insertedUser);
  //d($_GET['action']);
?>
<form class="d-flex justify-content-center" accept-charset="utf-8" action="admin.php?action=register" method="post" enctype="multipart/form-data">

  <?php //vyznam tohto? kvoli admin route?
  ?>
  <input type="hidden" name="register" value="true" />

  <?php /*/ ?>
  if (isset($results['errorMessage'])) { ?>
    <iframe class="errorMessage" style="display:none" onload="alert('<?=  $results['errorMessage'] ?>')"></iframe>
  <?php } ?>
  <?php /*/ ?>

  <div class="regForm">
    <div class= "regForm d-flex flex-column justify-content-center  mb-3">

    <?php //If there is problem, error defined in admin function will be shown
     if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
    <?php } ?>

    <input class="" type="hidden" name="registrationDate" id="registrationDate" value="<?=  date('Y-m-d') ?>" />

    <input class="" type="hidden" name="lastVisit" id="lastVisit" value="<?=  date('Y-m-d') ?>" />

      <div class="loginContainer">
        <label for="username">Meno</label>
        <input type="text" name="username" id="username" class="hideFooterOnMobile" placeholder="používateľské meno" required autofocus maxlength="20"  />
      </div>

      <div class="loginContainer">
        <label for="password">Heslo (minimálne 6 znakov)</label>
        <input type="password" name="password" id="password" class="hideFooterOnMobile" placeholder="heslo" required minlength ="6" maxlength="20" />
      </div>

      <div class="loginContainer">
        <label for="password_repeat">Zopakujte heslo</label>
        <input type="password" name="password_repeat" id="password_repeat" class="hideFooterOnMobile" placeholder="heslo" required  minlength ="6" maxlength="20" />
      </div>


      <div class="fullsize">
        <input type="checkbox" onclick="showText()">Zviditeľniť znaky
      </div>

      <div class="loginContainer">
        <label for="email">eMail</label>
        <input type="email" name="email" id="email" class="hideFooterOnMobile" placeholder="email"  required maxlength="50"/>
      </div>

      <small style="display: none" id="error-message" class="text-danger">Heslá sa nezhodujú.</small>

        <input type="hidden" readonly name="role" id="role" value="1" />
        <input type="hidden" type="file" name="image" id="image"/>
        <input type="hidden" name="aboutUser" />
    </div>

    <div class="logRegBtns">
      <button class="btn btn-small disabled" type="submit" id="submitRegister"  name="register" disabled="true" >Registrácia</button>

    </div>
  </div>
</form>

<script src="././assets/js/registration.js?v=<?=  time(); ?>"></script>

<?php /*/ ?>
<script src="././assets/js/hideFooter.js?v=<?=  time(); ?>"></script>
<?php /*/ ?>

<?php include "templates/include/footer.php" ?>

