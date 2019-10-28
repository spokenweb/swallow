<?php

function displayAlert($msg){
    echo("<script>alert('".$msg."')</script>");
    return true;
}

function redirect($url){
    echo("<script>window.location.replace('".$url."');</script>");
    return true;
}

?>