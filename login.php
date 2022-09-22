<?php 
  session_start();
  if($_POST){
    include 'db.config.php';
    if(isset($_POST['uname']) AND isset($_POST['password'])){
      $user=validate_user($_POST['uname'],urldecode($_POST['password']));
      if(isset($user['uname'])){
        if(intval($user['admin'])==1 or intval($user['staff'])==1){
          $_SESSION['uid']=$user['uid'];
          $_SESSION['admin']=$user['admin'];
          $_SESSION['staff']=$user['staff'];
          header('Location: admin121245/index.php');
          exit();
        }
        $_SESSION['username']=$user['uname'];
        $_SESSION['uid']=$user['uid'];
        header('Location: home.php');
        exit();
      }
      else{
        $err="error";
        $expiree= time() + (1);
        setcookie($err, "Username or password is incorrect", $expiree);
        header("Location: login.php");
        exit();
      }
    }
    else{
        $err="error";
        $expiree= time() + (1);
        setcookie($err, "Please fill all the fields", $expire);
        header("Location: login.php");
        exit();
    }
  }
  else{
    if(isset($_SESSION['username'])){
      header("Location: home.php");
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Signup</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/my.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css" />
  </head>
  <body>
 
  <?php 
      $page=0; #0 index.php & home.php, 1 courses.php, 3 cart.php, 4 mycourses.php
      include("components/header.php"); 
    ?>
    <div class="loginbox col-md-offset-4 col-md-4">
      <img
        src="images/Component 7 – 1.png"
        class="img-responsive profileview"
      />
      <br />
      <?php
        if(isset($_COOKIE['error'])){
          echo "<span class='text-warning'>".$_COOKIE['error']."</span>";
        }
        ?>
      <form class="inputform" name="login" method="post" action="login.php">
        <label>Username</label><br />
        <input
          type="text"
          name="uname"
          placeholder="username"
          required="true"
          pattern="^[A-Za-z0-9]{3,}$"
          title="Input only contain letters"
        />
        <br />
        <br />
        <label>Password</label>
        <br />
        <input
          type="password"
          name="password"
          placeholder="********"
          required="true"
        />
        <br />
        <br />
        <button class="btn btn-ls">Log In</button>
      </form>
      <div class="haveacc">
        <a href="signup.php">Don't have an account?</a>
      </div>
    </div>
    <script language="javascript" src="js/index.js"></script>
    <script type="text/javascript">
      function subm(){
        document.forms.login.submit();
      }
    </script>
  </body>
</html>
