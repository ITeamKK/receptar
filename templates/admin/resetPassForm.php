<?php include "templates/include/header.php"; //SESSION START ?>

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
/*   d($_SERVER);
  d($tokenFromMail); */
?>
<form class="neomorf_1_outer d-flex justify-content-center" accept-charset="utf-8" action="admin.php?action=setNewPassword" method="post" enctype="multipart/form-data">

  <input type="hidden" name="register" value="true" />

  <?php /*/ ?><?php /*/ ?>
  <?php
  if (isset($results['errorMessage'])) { ?>
    <iframe class="errorMessage" style="display:none" onload="alert('<?=  $results['errorMessage'] ?>')"></iframe>
  <?php } ?>

  <div class="neomorf_1_inner  m-5 p-5 regForm">
    <div class= "d-flex flex-column justify-content-center align-items-center ">

    <?php //If there is problem, error defined in admin function will be shown
     if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
    <?php } ?>

      <div>
        <label for="password">Heslo (minimálne 6 znakov)</label>
        <input type="password" name="password" id="password" placeholder="heslo" required minlength ="6" maxlength="20" />
      </div>

      <div>
        <label for="password_repeat">Zopakujte heslo</label>
        <input type="password" name="password_repeat" id="password_repeat" placeholder="heslo" required  minlength ="6" maxlength="20" />
      </div>


      <div>
        <input type="checkbox" onclick="showText()">Zviditeľniť znaky
      </div>

        <div>
        <input type="hidden"  name="token" id="token" value="<?=  $tokenFromMail;?>">
      </div>

      <small style="display: none" id="error-message" class="text-danger">Heslá sa nezhodujú.</small>

    </div>

    <div class="logRegBtns m-5">
      <button class="btn neomorf_1_inner m-2 p-5 disabled" type="submit" id="submitRegister"  name="register" disabled="true" >Uložiť nové heslo</button>

    </div>
  </div>
</form>

<script src="././assets/js/registration.js?v=<?=  time(); ?>"></script>

<?php include "templates/include/footer.php" ?>

