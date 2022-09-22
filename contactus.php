<?php
  if(isset($_POST['email'])){
    include("db.config.php");
    set_message($_POST['email'],$_POST['msg']);
    exit();
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elearn</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/my.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css" />
</head>
<body style="padding-top: 100px">
    <?php 
      $page=-1; #0 index.php & home.php, 1 courses.php, 3 cart.php, 4 mycourses.php
      include("components/header.php"); 
    ?>
    <div class="edit-overlay" style="display: none">
      <div>
        <h3>Thank you for submission.</h3>
        <button class="btn btn-default" style="float: right" onclick="window.location='index.php';">OK</button>
      </div>
    </div>
    <div class="container course-upload-container">
        <div class="col-md-offset-3 col-md-6">
            <h4>We'll reply you through your email as soon as possible.</h4>
            <span style="color: red; display: none">All fields must be filled</span>
            <span style="color: red; display: none">Email Must be in the correct format(yourname@provider)</span>
            <form name="message">
                <label for="email" class="title">Email</label>
                <input type="email" name="email" required>
                <br>
                <label for="msg" class="description">Message</label>
                <textarea name="msg" rows="5" required></textarea>
                <br>
                <a class="btn btn-default send" onclick="subm();">Send</a>
        </form>
        </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script>
      function subm(){
        var form = document.message;
        var test=/([a-zA-Z0-9]@[a-z].[a-z])/;
        if(form.email.value=="" || form.msg.value==""){
          document.getElementsByTagName("span")[1].style.display = "block";
          console.log("loadg");
          return null;
        }
        if(!test.test(form.email.value)){
          document.getElementsByTagName("span")[1].style.display = "none";
          document.getElementsByTagName("span")[2].style.display = "block";
          return null;
          console.log("log");
        }
        document.getElementsByTagName("span")[1].style.display = "none";
        document.getElementsByTagName("span")[2].style.display = "none";
        var req=`email=${form.email.value}&msg=${form.msg.value}`;
        var url="http://localhost/Elearn/contactus.php";
        var xhr=new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
        xhr.send(req);
        xhr.onload = function(){
          document.getElementsByClassName("edit-overlay")[0].style.display="flex";
        }
            }
    </script>
</body>
</html>