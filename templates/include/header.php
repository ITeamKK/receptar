<?php
if (!isset($_SESSION)) //to no to run SESSION more than once. Its called also in admin.php
{
  session_start();
}
?>
<?php //session_start();
?>
<?php //Session_start() carries information about current session-i.e. if user is logged in, etc.
/*Functions that send/modify HTTP headers like Session_start() must be invoked before any output is made. Otherwise the call fails. */
/*Intentional output from print and echo statements will terminate the opportunity to send HTTP headers. The application flow must be restructured to avoid that. */
//session_start(); //returns true if session was succesfully started, otherwise false, creates a session or resumes the current one
?>
<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="utf-8">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title><?= htmlspecialchars($results['pageTitle']) ?></title>

  <meta name="description" content="Recepty kuchyne slovenska, spanielska a tak.">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- FAVICON -->
  <link rel="icon" type="image/png" href="./assets/images/favicon/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">
  <!-- BOOTSTRAP -->
  <!--   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
 -->
  <!-- CUSTOM.CSS -->
  <link rel="stylesheet" href="./assets/css/reset.css?v=<?= time(); ?>">
  <link rel="stylesheet" href="./assets/css/style.css?v=<?= time(); ?>">
  <!--  <link rel="stylesheet" href="./assets/css/style copy.css?v=<?= time(); ?>"> -->
  <link rel="stylesheet" href="./assets/css/colors.css?v=<?= time(); ?>">
  <!-- jQuery 1.8 or later, 33 KB -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <!-- Fotorama from CDNJS, 19 KB -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
  <!-- Font Awesome -->
  <script src="https://kit.fontawesome.com/ed0737879b.js" crossorigin="anonymous"></script>
  <!-- FeatherIcons.com -->
  <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
  <!-- GOOGLE FONT -->
  <link href="https://fonts.googleapis.com/css2?family=Cabin&display=swap" rel="stylesheet">

</head>

<body>
  <header class="">

    <!--LOGO - SK-->
    <figure id="logoContainer" class="">
      <a href=".">
        <img id="logo" class="logo" src="/assets/images/logo_sk.png" alt="Recepty Team KK">
      </a>
    </figure>

    <!--MENU ITEMS - SPECIFIC TO USER LEVEL -->
    <div class="header_info">
      <?php
      if (isset($_SESSION['username'])) {
      ?>
        <div>
          <a class=""href=" admin.php?action=userDetails"> <?= htmlspecialchars($_SESSION['username']) ?> <i class="fas fa-user"></i> </a>
        </div>
        <div><a class="" href="admin.php?action=logout"><i class="fas fa-sign-out-alt"></i> </a>
        </div>
      <?php } ?>
  
      <?php
      // d(checkAdmin(),$_GET,$_SESSION);
      if (isset($_SESSION['username']) && checkAdmin()) {
        //if user is admin, it will be possible to changed categories
      ?>
       <div>  <a class="" href="admin.php?action=listCategories">Upraviť kategórie</a></div>
      <?php /*/ ?>
       <div> <a class="" href="admin.php?action=dbManagement">DB management</a></div>
       <?php /*/ ?>
        <?php /*/ ?><p><?=  TODAY ?></p><?php /*/ ?>
      <?php } ?>

      <?php //FAVOURITES HEART ICON shown if logged in
      if (isset($_SESSION['username'])) {

      ?>
  
        <div class="likeContainer">
          <a class="  tooltip" href="admin.php?action=favourites">
            Moje obľúbené recepty
            <i id="iconFavouriteHeader" class="<?php
                                                //this code looks into db and when there are some favourites, it will be true
                                                if (!empty(User::getByUsername($_SESSION['username'])->favouriteArticles)) {
                                                  echo 'fas';
                                                } else {
                                                  echo 'far';
                                                }
                                                ?> fa-heart p-1"></i>
            <div class="tooltiptext">Obľúbené recepty</div>
          </a>
        </div>

        <div>
          <a class="" href="admin.php?action=newArticle">
            Pridať recept
            <i class="fas fa-plus-square"></i>
          </a>
        </div>


      <?php } else { ?>
        <nav class="menu_header">
          <a href="admin.php">Prihlásiť sa</a>
          <a href="admin.php?action=register">Registrovať sa</a>
          <button class="darkModeSwitch" id="switch"> <i class="fas fa-moon"></i>
            <span></span>
            <span></span>
          </button>

        </nav>


        <div class="burger" id="burgerMenu">
          <i class="fas fa-bars"></i>
        </div>

      <?php } ?>
    </div>
  </header>

  <!-- SHRINK HEADER JS -->
  <script src="./assets/js/shrinkHeader.js?v=<?= time(); ?>"></script>
  <main>

    <?php
    //d($GLOBALS);
    ?>