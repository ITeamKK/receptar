<?php include "templates/include/header.php"; //SESSION START
?>



<form class=" d-flex justify-content-center flex-column align-items-center" accept-charset="utf-8" action="templates/include/sendmail.php" method="post" enctype="multipart/form-data">
    <h4 class="m-5">Pošli nám správu cez tento kontaktný formulár!</h4>
    <div class="  m-5 p-5 contactForm">
        <div class="d-flex flex-column justify-content-center align-items-center ">
   
            <div class="loginContainer">
                <label for="Name" class="">Meno *</label>
                <input type="text" id="Name" name="Name" class="hideFooterOnMobile" maxlength="20" required>
            </div>

            <div class="loginContainer">
                <label for="Email" class="">Email kde Vás môžme kontaktovať *</label>
                <input type="email" id="Email" name="Email" class="hideFooterOnMobile" maxlength="30" required>
            </div>

            <div class="loginContainer">
                <label for="Message" class="">Správa *</label>
                <textarea class="contactTextMessage hideFooterOnMobile" id="Message" name="Message" class="hideFooterOnMobile" rows="3" maxlength="200" required></textarea>
            </div>


            <div class="logRegBtns">
                <button class="btn btn-small" type="submit" id="submitContactForm" name="submitContactForm">Odoslať</button>
            </div>

        </div>
    </div>
</form>

<?php /*/ ?>
<script src="././assets/js/hideFooter.js?v=<?=  time(); ?>"></script>
<?php /*/ ?>
<?php include "templates/include/footer.php" ?>