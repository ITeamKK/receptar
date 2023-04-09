</main>

<footer class="">
  <div class="d-flex justify-content-center">
    <p class="footerElement"><a href="./?action=policy">Podmienky ochrany osobných údajov</a></p>

    <p class="separator"> | </p>

    <p class="footerElement"><a href="./?action=aboutUs">O nás</a></p>

    <p class="separator"> | </p>

    <p class="footerElement"><a href="./?action=contactForm">Kontaktný formulár</a></p>

    <p class="separator"> | </p>

    <p class="footerElement">teamKK &copy; 2014 - <?=  date("Y"); ?></p>
  </div>

  <!--certbot spanielsky -->
  <div class="certBot">
    <script src="https://www.gstatic.com/dialogflow-console/fast/messenger/bootstrap.js?v=1"></script>
    <df-messenger chat-icon="https://storage.googleapis.com/cloudprod-apiai/87f86ed5-7003-4382-a4f4-c61e54bb7788_x.png" chat-title="CertBot" agent-id="74ba2920-2a29-48d8-8969-5d51f74144e1" language-code="es"></df-messenger>
    <style>
      /*vobec toto neberie do uvahy.. */
      df-messenger-chat div.chat-wrapper[opened="true"] {
        height: 75%;
        height: 450px;
      }

      df-messenger {
        --df-messenger-z-index: 10000;
        --df-messenger-bot-message: #878fac;
        --df-messenger-button-titlebar-color: #df9b56;
        --df-messenger-chat-background-color: #fafafa;
        --df-messenger-font-color: white;
        --df-messenger-send-icon: #878fac;
        --df-messenger-user-message: #479b3d;
        font-family: cursive;
      }
    </style>
  </div>
</footer>

<!-- CUSTOM.JS -->
<? /*/ ?><? /*/ ?>
<script src="./assets/js/mobile_menu.js?v=<?=  time(); ?>"></script>


</body>

</html>