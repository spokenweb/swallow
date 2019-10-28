<html lang="en">
<head>
  <title>SWALLOW | Metadata Ingestion System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
  <link href="./styles.css" rel="stylesheet" type="text/css" />

  
</head>
<body>

<!------ Include the above in your HEAD tag ---------->

<div class="wrapper fadeInDown">
  <div id="formContent">
    <!-- Tabs Titles -->

    <!-- Icon -->
    <div>
      <div>
        <br />
        <img src="images/logo-image.png">
        <h5>Spoken Web Audio Metadata Ingest System</h5>
      </div>
      <br />
      <p>
        Please insert your email. <br />
        A temporary password would be sent to you. 
      </p>
      <br />
    </div>


    <!-- Login Form -->
    <form action="Controller/forgot-password.php" method="POST">
      <input type="text" id="email" name="email" placeholder="email">
      <input type="submit" value="Send">
    </form>

     <div id="formFooter">
      <a class="underlineHover" href="index.php">Back to the main page</a>
    </div>

  </div>
</div>

</body>
</html>