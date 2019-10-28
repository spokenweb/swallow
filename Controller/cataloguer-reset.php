<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";
require_once "../Model/session.php";
isLogged($conn);


$objCataloguer = new Cataloguer($conn);

if(isset($_GET['id'])){
    // Sent email with the credentials

    $objCataloguer->select($_GET['id']);

    $newPwd = bin2hex(openssl_random_pseudo_bytes(6));
    $mailHTML = "
    <!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
    <html>
        <body>
            <p>Hi ". $objCataloguer->name.",</p> 
            <p>Welcome to Swallow: Spoken Web Audio Metadata Ingest System</p>
            <p>This is your login information:</p>
            <p> <b>URL: <a href='http://swallow.library.concordia.ca'>http://swallow.library.concordia.ca</a></p>
            <p> <b>Your temporary password: </b>".$newPwd ."<p>
            <br />
            <p> Please, remember to change your password after login in for the first time </p>
            <br />
            <p>Kind regards,</p>
            <p>Swallow team</p>

        <body>
    </html>";	

    $subject = "Your SWALLOW credentials";

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: Swallow <noreply@concordia.ca>';		


    if (mail($objCataloguer->email , $subject, $mailHTML, $headers)){
        $objCataloguer->pwd = password_hash($newPwd, PASSWORD_DEFAULT);
        $objCataloguer->save();
        echo("true");
    }else{
        echo("false");
    }

}




?>