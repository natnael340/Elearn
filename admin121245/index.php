<?php
  session_start();
  if(!isset($_SESSION['uid'])){
      header("Location: ../login.php");
  }
  if(!isset($_SESSION['admin']) or !isset($_SESSION['staff'])){
    header("Location: ../404.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elearn</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/my.css" />
    <link
      rel="stylesheet"
      type="text/css"
      href="../font-awesome-4.7.0/css/font-awesome.css"
    />
  </head>
  <body>
    <header class="header fixed">
      <nav class="navbar navbar-style">
        <div class="container-fluid">
          <div class="navbar-header">
            <img src="../images/book-icon.png" alt="Logo" class="logo" />
          </div>
          <ul class="nav navbar-nav navbar-right nav-menu">
            <li><a href="../logout.php">Logout</a></li>
          </ul>
        </div>
      </nav>
    </header>
    <div class="container">
      <h3>Tables</h3>
      <ul>
        <li><a href="users.php">users</a></li>
        <li><a href="billing.php">billing</a></li>
        <li><a href="courses.php">courses</a></li>
        <li><a href="messages.php">messages</a></li>
      </ul>
    </div>
  </body>
</html>
