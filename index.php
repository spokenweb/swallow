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
  <link href="https://fonts.googleapis.com/css?family=Roboto:100" rel="stylesheet">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="./styles.css" rel="stylesheet" type="text/css" />

  
</head>
<body class='login-bg'>

<!------ Include the above in your HEAD tag ---------->

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div>
      <br />
      <img src="images/logo-image.png">
      <h5>SpokenWeb Audio Metadata Ingest System</h5>
      <small> Version 1.2 </small>
    </div>

    <!-- Login Form -->
    <form action="Controller/login.php" method="POST">
      <input type="text" id="login" name="login" placeholder="email">
      <input type="password" id="pwd"   name="pwd" placeholder="password">
      <input type="submit" class="fadeIn fourth" value="Log In">
    </form>

    <!-- Remind Passowrd -->
    <div id="formFooter">
      <a class="underlineHover" href="forgot-password.php">Forgot Password?</a>
    </div>


  </div>
</div>

</body>
</html>
