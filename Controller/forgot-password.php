<?php
require_once "../Model/db.config.php";
require_once "../Model/cataloguer.php";
include "../util.php";

if( isset($_POST['email']) and $_POST['email'] != '' and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
    
    $objCataloguer = new Cataloguer($conn);
    $objCataloguer->selectFromEmail($_POST['email']);

    if($objCataloguer->total > 0){
        
        $pwd = bin2hex(openssl_random_pseudo_bytes(6)); //  generate a random password
        $objCataloguer->pwd = password_hash($pwd, PASSWORD_DEFAULT);
        $objCataloguer->save();
               
        $mailHTML = "
        <!DOCTYPE html PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
        <html>
            <body>
                <p>Here's your temporary password: <b>".$pwd."</b><p>
                <p>Please change this temporary password next time you log in</p>
            <body>
        </html>";	

        $subject = "SWALLOW password reminder";

        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: Swallow <noreply@concordia.ca>';		
        
        
        if( mail($_POST['email'], $subject, $mailHTML, $headers) ){
            displayAlert("You will receive and email very shorly with your temporary password");      
            redirect("../index.php");      
        }else{
            redirect("../forgot-password.php?err=Something went wrong sending your the email with the instructions. Please try again. If the problem persist please contact us");
        }

   }else{
        displayAlert("The email does not have an associated account");  
        redirect("../forgot-password.php");
   }



}else{
    //give error message and get back to forgot-password
    displayAlert("Please introduce a valid email");  
    redirect("../forgot-password.php");
}

$conn->close();

?>