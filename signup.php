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
    if($_POST){
      include 'db.config.php';
      if(isset($_POST['uname']) && isset($_POST['email']) && isset($_POST['password'])){
        $uname=register_user($_POST['uname'],$_POST['email'],$_POST['password'],1,0);
        if(isset($uname)){
          $name="success";
          $expire= time() + (1);
          setcookie($name, "Your are sucessfully registered", $expire);
          header("Location: signup.php");
          exit();
        }
      }
      
    }
  ?>
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
      <?php if(isset($_COOKIE['success'])) echo '<span class="text-info">'.$_COOKIE['success'].'</span>
      <script> setTimeout(function(){window.location="http://localhost/Elearn/login.php";},3000);</script>'; ?>
      <form class="inputform" method="POST" action="signup.php">
        <label>Username</label><br />
        <input
          type="text"
          name="uname"
          placeholder="username"
          required="true"
          pattern="^[A-Za-z0-9]{3,}$"
          title="Only letters & numbers allowed"
        />
        <br />
        <br />
        <label>Email</label>
        <br />
        <input
          type="email"
          name="email"
          placeholder="abc@gmail.com"
          required="true"
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
        <button class="btn btn-ls">Sign Up</button>
      </form>
      <div class="haveacc"><a href="login.php">already have an account?</a></div>
    </div>
    <script language="javascript" src="js/index.js"></script>
    <script lang="javascript">
      
    </script>
  </body>
</html>
