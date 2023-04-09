<?php /* This page is about username, password changes, about me textarea, photo, delete account*/ ?>

<?php include "templates/include/header.php"; //SESSION START
//d($_SESSION, $_SERVER, $results, $countArticlesUser, $currentUserData, $_POST, $_GET, $userAlreadyExists, $_FILES);
?>

<h2>Nastavenia môjho konta</h2>


<?php //ORANGE SECTION?>
<div class="userData p-4">
  <p>Počet mojich receptov : <?=  $countArticlesUser ?></p>
  <p>Registrovaný od : <?=  date('j F Y', $registeredFrom) ?></p>
</div>

<?php //ABOUT USER SECTION?>
<div class="containerAccount d-flex">

  <div class=" d-flex flex-column justify-content-center align-items-center">
    <h3>Moja fotografia a niečo o mne</h3>

    <?php //TEXTAREA ABOUT ME ?>
    <div class="">
      <label for="aboutUser">Niečo o mne</label>
      <textarea name="aboutUser" id="aboutUser" placeholder="Napíš niečo o sebe, napríklad - Som fanúšik dobrej kuchyne." maxlength="200" minlength="2" form="updateUserForm" rows="4" cols="30"><?=  htmlspecialchars($currentUserData->aboutUser) ?></textarea>

    </div>

    <?php //IMG PROFILE
    ?>
    <div class="editImg">
      <label class="">Profilový obrázok</label>

      <?php
      if ($imagePath = $currentUserData->getImagePath()) {
        // d($imagePath);
      ?>
        <img class="pridany_obrazok" src="<?=  $imagePath ?>" alt="User Image" />
      <?php } ?>
      <?php //DELETE IMG
      ?>
      <button class="btn  p-3 m-3" type="submit" name="deleteImage" form="updateUserForm">Vymazať obrázok
      </button>

      <div class="btn  p-3 m-3">
        <label class="" for="userImage">Nahrať fotku</label>
        <input class="" type="file" name="userImage" id="userImage" form="updateUserForm" />
      </div>
    </div>

    <div class="p-5">
      <?php //toto odosle do adminu v $_POST iny submit ako pri zmene mena a hesla, takze nepojde do casti kodu pre username/password
      ?>
      <button class="btn btn-small m-2 p-5 logRegBtn" type="submit" form="updateUserForm" name="submitUserInfoImgChanges" id="submitUserInfoImgChanges">
        Odoslať údaje
      </button>
    </div>
  </div>

  <?php //Action musi byt nastavena na switch v admin.php => tu je to userDetails ?>
  <form class=" d-flex justify-content-center" id="updateUserForm" action="admin.php?action=userDetails" method="post" accept-charset="utf-8" enctype="multipart/form-data">

    <div class="d-flex flex-column justify-content-center align-items-center userFormInner">
      <h3>Zmena mena alebo hesla</h3>
      <?php //If there is problem, error defined in admin function will be shown
      if (isset($results['errorMessageUsername'])) { ?>
        <div class="errorMessage"><?=  $results['errorMessageUsername'] ?></div>
      <?php } ?>
      <?php
      if (isset($results['errorMessagePassword'])) { ?>
        <div class="errorMessage"><?=  $results['errorMessagePassword'] ?></div>
      <?php } ?>

      <?php /*/ ?>
      <?php
      if (isset($results['successMessageUsername'])) { ?>
        <div class="errorMessage"><?=  $results['successMessageUsername'] ?></div>
      <?php } ?>
      <?php
      if (isset($results['successMessagePassword'])) { ?>
        <div class="errorMessage"><?=  $results['successMessagePassword'] ?></div>
      <?php } ?>
      <?php /*/ ?>

      <?php //toto odosle do adminu POST, takze kod moze pokracovat
      ?>
      <!-- <input type="hidden" name="userDetailsPosted" value="true" /> -->

      <div class="p-3">
        <?php /*/ ?>
      <p>Súčasné meno je : <?=  htmlspecialchars($_SESSION['username']) ?></p>
      <?php /*/ ?>
        <label for="changeUsername">Zadaj nové meno (max 20 znakov)</label>
        <input type="text" name="username" id="changeUsername" placeholder="nové meno" maxlength="20" minlength="3" onfocus="this.value=''" />
      </div>

      <div class="p-3">
        <?php /*/ ?>
      <p>Súčasné heslo je : <?=  htmlspecialchars($currentUserPassword) ?></p>
      <?php /*/ ?>
        <label for="changePassword">Zadaj nové heslo (min 6, max 20 znakov)</label>
        <input type="password" name="password" id="changePassword" placeholder="nové heslo" maxlength="20" minlength="6" value="" onfocus="this.value=''" />
      </div>

      <div class="logRegBtns">
        <?php //toto odosle do adminu POST, takze kod moze pokracovat
        ?>
        <button class="btn btn-small m-2 p-5 logRegBtn" type="submit" name="submitChanges" id="submitUserChanges">Odoslať údaje</button>
      </div>
    </div>
  </form>

</div>

<?php //DELETE ACCOUNT?>
<form class="d-flex justify-content-center" action="admin.php?action=deleteUser" method="post">
  <div class="tooltip">
    <button class="btn btn-small m-2 p-5 logRegBtn danger" type="submit" name="submitDeleteUser" id="submitDeleteUser" onclick="return confirm('Naozaj vymazať konto?\nTvoje konto bude vymazané ale všetky tvoje recepty zostanú dostupné pre ostatných užívateľov.')">Zmazať konto</button>
    <span class="tooltiptext">Vymaže užívateľské konto</span>
  </div>
</form>

<script></script>

<?php include "templates/include/footer.php" ?>