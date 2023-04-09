<?php

function problem($error)
{
    echo "We are very sorry, but there were error(s) found with the form you submitted. ";
    echo "These errors appear below.<br><br>";
    echo $error . "<br><br>";
    echo "Please go back and fix these errors.<br><br>";
    die();
}

$error_message = "";
$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';

if (!preg_match($email_exp, $email)) {
    $error_message .= 'The Email address you entered does not appear to be valid.<br>';
}

$string_exp = "/^[A-Za-z .'-]+$/";

if (!preg_match($string_exp, $name)) {
    $error_message .= 'The Name you entered does not appear to be valid.<br>';
}

/*    if (strlen($message) < 2) {
        $error_message .= 'The Message you entered do not appear to be valid.<br>';
    } */

if (strlen($error_message) > 0) {
    problem($error_message);
}

$email_message = "Form details below." . "<br>";

function clean_string($string)
{
    $bad = array("content-type", "bcc:", "to:", "cc:", "href");
    return str_replace($bad, "", $string);
}

/*     $email_message .= "Username: " . clean_string($name) . "\n";
    $email_message .= "Your Email: " . clean_string($email) . "\n";
    $email_message .= "Message: " . $message . "\n";
 *//*     $email_message .= "Enjoy HASH moron! " . "\n"; */



// create email headers
?>
    <?php
    // It is mandatory to set the content-type when sending HTML email
    //$headers = "MIME-Version: 1.0" . "\r\n";
    // $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; //for html tags to work

    // More headers. From is required, rest other headers are optional
    //arrays works from PHP version 7 and up
    $headers = array(
        'Content-type' => 'text/html;charset=UTF-8',
        'From' => 'webmaster@example.com', //ignored because of .user.ini
      //  'Cc' => 'iveta.karailievova@gmail.com',
        'Bcc' => 'iveta.karailievova@gmail.com',
        'Reply-To' => 'webmaster@example.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    $success = mail($email_to, $email_subject, $message, $headers);

    if (!$success) {
        $errorMessage = error_get_last()['message'];
    }
    ?>