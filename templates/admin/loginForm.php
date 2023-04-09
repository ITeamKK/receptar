<?php
//this page contains login form and reset password form
include "templates/include/header.php"; //SESSION START
// d($_SESSION, $_SERVER, $_POST,$currentUser);
//d($results,$lockedOutUser,$picikurva);
if (isset($tokenFromMail)) {
  //d($tokenFromMail);
}
?>


<form class="neomorf_1_outer d-flex justify-content-center" action="admin.php?action=login" method="post">

  <input type="hidden" name="login" value="true" />

  <div class="neomorf_1_inner m-5 p-5 loginForm">

    <div class="d-flex flex-column justify-content-center align-items-center ">

      <?php //If there is problem, error defined in admin function will be shown
      if (isset($results['errorMessage'])) { ?>
        <div class="errorMessage"><?=  $results['errorMessage'] ?></div>
      <?php } ?>

      <div class="loginContainer">
        <label for="loginUsername">Meno</label>
        <input type="text" name="username" id="loginUsername" class="hideFooterOnMobile" placeholder="používateľské meno" required maxlength="20" />
      </div>

      <div class="loginContainer">
        <label for="loginPassword">Heslo</label>
        <input type="password" name="password" id="loginPassword" class="hideFooterOnMobile" placeholder="heslo" required maxlength="20" />
      </div>
      <div class="fullSize">
        <input type="checkbox" onclick="showText()" id="seePassword">
        <label for="seePassword">Zviditeľniť znaky</label>
      </div>

      <div class="logRegBtns m-5">
        <button class="btn neomorf_1_inner m-2 disabled" type="submit" name="loginSubmit" disabled="true" id="submitLogin">
          Prihlásenie</button>
      </div>

    </div>
  </div>
</form>

<div class="neomorf_1_outer d-flex justify-content-center mt-5">
  <div class="logRegBtns">
    <button class="btn neomorf_1_inner" type="" name="forgottenSubmit" id="" onclick='showEmailForm();location="#resetPasswordSubmit"'>Zabudol som heslo</button>
  </div>
</div>

<?php //FORGOTTEN PASSWORD
?>
<form class="mt-3 neomorf_1_outer d-flex justify-content-center" action="admin.php?action=resetPassword" method="post">



  <div class="neomorf_1_inner  m-5 p-5 resendPass loginForm" id='resendDiv'>
    <h4 id="formForgottenTitle">Reset zabudnutého hesla cez email</h4>

    <div class="loginContainer">
      <label for="resetPasswordName">Meno</label>
      <input type="text" name="resetPasswordName" id="resetPasswordName" class="hideFooterOnMobile" placeholder="používateľské meno" required maxlength="20" />
    </div>

    <div class="loginContainer">
      <label for="inputEmail">Emailová adresu použitá pri registrácii</label>
      <input type="email" name="email" id="inputEmail" class="hideFooterOnMobile" placeholder="email" maxlength="30" required />
    </div>

    <div class="logRegBtns">
      <button class="btn neomorf_1_inner" type="submit" name="resetPasswordSubmit" id="resetPasswordSubmit">Zresetovať heslo</button>
    </div>
  </div>


</form>

<?php //If there is problem, error defined in admin function will be shown
if (isset($results['errorMessageResetPassword'])) { ?>
  <div class="errorMessage"><?=  $results['errorMessageResetPassword'] ?></div>
<?php } ?>
<?php //If there is problem, error defined in admin function will be shown
if (isset($results['successMessageResetPassword'])) {
  $result = '
        <script> alert("' . $results['successMessageResetPassword'] . '"); </script>';
  echo $result;
} ?>


<script src="././assets/js/login.js?v=<?=  time(); ?>"></script>
<script src="././assets/js/hideFooter.js?v=<?=  time(); ?>"></script>

<?php include "templates/include/footer.php" ?>