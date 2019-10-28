<?php
require_once "Model/db.config.php";
require_once "Model/session.php";
require_once "Model/cataloguer.php";

isLogged($conn);

$objCataloguer = new Cataloguer($conn);
$objCataloguer->select($_SESSION['swallow_uid']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SWALLOW | Metadata Ingestion System</title>
  <meta charset="utf-8">
  
  <link rel="apple-touch-icon" sizes="180x180" href="images/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="images/favicon-16x16.png">
  <link rel="manifest" href="images/site.webmanifest">
  <link rel="mask-icon" href="images/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="./styles.css" rel="stylesheet" type="text/css" />


  
</head>
<body>


<nav class="navbar top-nav-bar">

<a href="/main.php"><img src="images/logo-menu-image.png"></a>
  <span class='top-nav-bar-version'>Version 1.1 </span>
  <div class="top-nav-bar-profile">
       
      <?php echo($objCataloguer->name." ".$objCataloguer->lastname) ?>
       
      <div style="position:relative; float:right; padding-top: 10px;">
        <a class="nav-link dropdown-toggle " href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="glyphicon glyphicon-user top-nav-profile-icon"></span>   
        </a>

        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#" id="mmnu_myProfile">My Profile</a>
          <a class="dropdown-item" href="Controller/logout.php">Logout</a>
        </div>
      </div>
  </div>
  
</nav>



<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-2 sidenav" id="main-menu" >    

      <ul class="nav nav-pills nav-stacked">
        <li id="li_mmnu_home" class="active  main-menu">
            <a id="mmnu_home" href="#">
              <img src="images/menu-icon-dashboard.png">
              <span class='main-menu-help'>Dashboard</span>
            </a>
        </li>

        
        
        <?php 
          if(isAdmin($conn)){
            echo ("<li id='li_mmnu_manage_users' class='main-menu'>
                    <a id='mmnu_manage_users' href='#'>
                      <img src='images/menu-icon-cataloguer.png'>
                      <span class='main-menu-help'>Cataloguers</span>
                    </a>
                  </li>");
          }
        ?>
        <li id="li_mmnu_manage_collections" class="main-menu">
          <a id="mmnu_manage_collections" href="#">
            <img src="images/menu-icon-collection.png">
            <span class='main-menu-help'>Collections</span>
          </a>
        </li>

        <li id="li_mmnu_manage_items" class="main-menu">
          <a id="mmnu_manage_items" href="#">
            <img src="images/menu-icon-item.png">
            <span class='main-menu-help'>Items</span>
          </a>
        </li>

        <hr />

        <li id="li_mmnu_import" class="main-menu">
          <a id="mmnu_import" href="#">
            <img src="images/menu-icon-import.png">
            <span class='main-menu-help'>Import</span>
          </a>
        </li>
        
        <li id="li_mmnu_export" class="main-menu">
          <a id="mmnu_export" href="#">
            <img src="images/menu-icon-export.png">
            <span class='main-menu-help'>Export</span>
          </a>
        </li>
        
      
    </div>

    <div id="main" class="col-sm-10">

    </div>

  </div>
</div>

<!-- 
<footer class="container-fluid">
  <p>Swallow: SpokenWeb Audio Metadata Ingest System | Alpha 0.1</p>
</footer>
        -->
<script src='main.js' ></script>
</body>
</html>
