<?php
    session_start();
    include("../db.config.php");
    if(!isset($_SESSION['admin']) or !isset($_SESSION['staff'])){
      header("Location: ../404.html");
      exit();
  }
    if(!isset($_SESSION['uid'])){
        header("Location: ../login.php");
        exit();
    }
    if(intval($_SESSION['admin'])!=1){
        header("Location: index.php");
        exit();
    }
    if(isset($_POST['balance'])){
      update_balance(intval($_POST['update']),doubleval($_POST['balance']));
      exit();
    }
    $data=retrive_user_biiling();
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
    <div class="container admin">
      <h3>Billing</h3>
      <hr>
      <table>
          <tr>
              <th>Id</th>
              <th>usename</th>
              <th>Full Name</th>
              <th>Card Number</th>
              <th>CVV</th>
              <th>Expire Date</th>
              <th>Balance</th>
              <th></th>
          </tr>
          <?php
            foreach($data as $d){
                echo ' <tr>
                <td>'.$d['uid'].'</td>
                <td>'.$d['uname'].'</td>
                <td>'.$d['fname'].'</td>
                <td>'.$d['cardnum'].'</td>
                <td>'.$d['cvv'].'</td>
                <td>'.$d['exp'].'</td>
                <td><input type="number" name="balance" value="'.$d['balance'].'" id="b-'.$d['uid'].'"> </td>
                <td><button class="btn btn-default" onclick="save('.$d['uid'].')">Save</button></td>
            </tr>';
            }
          ?>
         
      </table>
    </div>
    <script>
        function save(id){
            var v=parseFloat(document.getElementById("b-"+id).value);
            var req="update="+id+"&balance="+v;
            var url="http://localhost/Elearn/admin121245/billing.php";
            var xhr=new XMLHttpRequest();
            xhr.open("POST", url, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
            xhr.send(req);
            xhr.onload = function(){
                window.location="http://localhost/Elearn/admin121245/billing.php";
            }
        }
    </script>
  </body>
</html>
