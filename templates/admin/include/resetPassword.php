<?php

$data = array();
$resetPwdToken =  bin2hex(random_bytes(50));
$data['token']=$resetPwdToken;
$data['email']= $lockedOutUser->email;
$tokenToReset = new Security;
$tokenToReset->storeFormValues($data);
$tokenToReset->insert();

//if entry was success, we continue
$insertedToken = Security::getByEmail((string)($lockedOutUser->email));

if($insertedToken == false){
    $results['errorMessageResetPassword'] = "Problém so zápisom do DB.";
    exit(0); //if entry doesnt exist, we stop code before sending email
}
