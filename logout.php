<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="de">
  <head>
      <meta name="viewport" content="width=device-width, initial-scale=1">      
      <link rel="stylesheet" href="MVD.css">
      <link rel="stylesheet" href="w3.css">
      <?php
          include 'include.php';
      ?>
      <!-- successfully included php libraries -->
      <?php
        mysqli_select_db($GLOBALS['conn'], $config['database']) or die(mysqli_error($conn));
      ?>
      <!-- successfully connected to MySQL database -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title><?php echo $commonStrings['WebSiteName']; ?></title>
  </head>
<body>
<div class="w3-container w3-teal">
<h1><?php echo $commonStrings['WebSiteName']; ?></h1>
</div>
<meta http-equiv="refresh" content="3; URL='login.php'" />
  <div class="w3-panel w3-mobile w3-center w3-green"><h2>Logout erfolgreich.</h2></div>
<?php
include "footer.php";
?>
