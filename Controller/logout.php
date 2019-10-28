<?php

session_start();
$_SESSION['swallow_uid'] = "";
session_destroy();

header("Location: ../index.php");

?>