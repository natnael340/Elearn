<?php
  session_start();
  if(!isset($_SESSION['uid'])){
    header("Location: login.php");
    exit();
  }
  
  include("db.config.php");
  $config=include("config.php");
  if(isset($_POST['lock'])){
    lock_unlock($_SESSION['uid'],$_POST['id'],$_POST['lock']);
    exit(); 
  }
  if(isset($_POST['del_enrolled'])){
    del_enrolled_course($_SESSION['uid'],$_POST['cid']);
  }
  if(isset($_POST['payment'])){
    modify_payement_info($_SESSION['uid'],$_POST['cardhname'], $_POST['cardnum'],$_POST['expdate'],$_POST['cvv']);
  }
  if(isset($_POST['edit_pro'])){
    edit_user($_SESSION['uid'],$_POST['email'], $_POST['bio'], $_POST['password']);
  }
  $error=isset($_COOKIE['error']) ? $_COOKIE['error']:null;
  $user= view_profile($_SESSION['uid'],(isset($_GET['uname']) ?  $_GET['uname']:null));
  
  $rows = array(
    "i" => 0,
    "f" => 100000
  );
  $course = retrive_course($rows,$user['id'],null,null,null);
  #print_r($course);
  $enrolled = retrive_enrolled_course($user['id']);
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
<body>
<?php 
      $page=-1; #0 index.php & home.php, 1 courses.php, 3 cart.php, 4 mycourses.php
      include("components/header.php"); 
    ?>
     <div class="edit-overlay" style="display: none">
      <div style="display: none">
        <form name="edit-pro" method="POST">
          <label for="email">Email</label><br>
          <input type="email" name="email" value="<?php echo $user['email'];?>"><br>
          <label for="bio">Bio</label><br>
          <input type="text" name="bio" value="<?php echo $user['bio'];?>"><br>
          <label for="password">Password</label><br>
          <input type="password" name="password" value=""><br><br>
          <button class="btn btn-default save" name="edit_pro" >Save</button>
          <a class="btn btn-default" onclick="chng_display(-1)" >Cancel</a>
        </form>
      </div>
     <div style="display: none">
     <form name="edit-payment" method="POST">
          <label for="cardhname">Cardholder Name</label><br>
          <input type="text" name="cardhname" value="<?php echo ucwords($user['fname']); ?>" required="true" ><br>
          <label for="cardnum">Card Number</label><br>
          <input type="text" name="cardnum" value="<?php echo $user['card_num'] ?>" required="true" ><br>
          <label for="expdate">Expiration Date</label><br>
          <input type="text" name="expdate" value="<?php echo $user['exp'] ?>" required="true" ><br>
          <label for="cvv">CVV</label><br>
          <input type="text" name="cvv" value="<?php echo $user['cvv'] ?>" required="true" ><br><br>
          <button class="btn btn-default save" name="payment" >Save</button>
          <a class="btn btn-default" onclick="chng_display(-1)" >Cancel</a>
        </form>
     </div>
    </div>
    <div class="container pro-container">
        <div class="pro-row">
            <div class="">
                <div class="propic">
                    <i class="fa fa-user"></i>
                </div>
            </div>
            <div class="pro-info">
                <h1><?php echo ucwords($user['uname']) ?></h1>
                <span><?php echo $user['email']?></span>
                <p><?php echo $user['bio'] ?></p>
                <?php
                if(!isset($_GET['uname']))  
                echo '<a href="#" onclick="chng_display(0)">Edit info</a>';
                ?>
               
            </div>
            <div class="course-overview">
                <div class="upload-container">
                    <h3><?php echo intval($user['uc']) ?></h3>
                    <h3>Upload</h3>
                </div>
                <div class="view-container">
                    <h3><?php echo intval($user['view']) ?></h3>
                    <h3>Views</h3>
                </div>
                <div class="course-container">
                    <h3><?php echo intval($user['ec']) ?></h3>
                    <h3>Enrolled Course</h3>
                </div>
            </div>
        </div>
        <br>
        <br>
        <?php
        if(!isset($_GET['uname']))  
        include("components/payment.info.php");
        ?>
        <div class="header">
                <h3>Courses</h3>
                <?php
                if(!isset($_GET['uname']))  
                echo '<a href="uploadCourse.php" class="btn btn-default">Upload</a>';
                ?>
                
            </div>
            <hr>
        <?php 
        foreach($course as $data){
          if(!(isset($_GET['uname']) and intval($data['private'])==1 and intval($data['active'])==0)){
            echo '<div class="col-md-4">
            <div class="content-container">
              <img src="'.$config['ctd'].$data['thumb'].'" alt="">
              <div class="content-txt-container">
                <h3 class="title">'.$data['title'].'<i class="fa fa-lock" id="lock-'.$data['cid'].'" style="display:'.(intval($data['private'])==1 ? 'block':'none').'" ></i></h3>
                <p>'.substr($data['desc'],0,20).'<a href="./course.php?cid='.$data['cid'].'">...see more</a></p>
              </div>
              <br>
              <br>
              '.(isset($_GET['uname']) ? '':' <div class="touchable">
                  <button class="button btn btn-default" onclick="unlock(this,'.$data['cid'].');" >
                      <i class="fa fa-unlock"></i>
                  </button>
                  <button class="button btn btn-default" onclick="window.location=\'uploadCourse.php?data='.$data['cid'].'\';" >
                      <i class="fa fa-pencil"></i>
                  </button>
              </div>
              ').'
            </div>
          </div>';
          }
          
        }?>
        <?php 
          if(!isset($_GET['uname'])){
            include("components/enrolled.php");
          }
        ?>
        
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
</body>
</html>