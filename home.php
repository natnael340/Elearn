<?php
  session_start();
  $search;
  if(!isset($_SESSION['username'])){
    header("Location: index.php");
    exit();
  }
  if(isset($_GET['s'])){
    $search=$_GET['s'];
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/my.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css" />
</head>
<body>
  <?php 
    $page=0; #0 index.php & home.php, 1 courses.php, 3 cart.php, 4 mycourses.php
    include("components/header.php"); 
  ?>
    
    <div class="container home-container">
      <div class="col-12">
        <h3><?php echo isset($search) ? htmlspecialchars_decode($search):"Recent"?></h3>
        <hr>
        <div class="row">
        <?php 
          include("db.config.php");
          $config=include("config.php");
          $row=array(
            "i"=> 0,
            "f"=> 20,
          );
          $data=retrive_course($row,null,null,null,null);
          if(isset($search)){
            $data=retrive_course($row,null,null,null,$_GET['s']);
          }
          
          foreach($data as $da){
            echo '
              <div class="col-md-4">
                <div class="content-container">
                  <img src="'.$config['ctd'].$da['thumb'].'" alt="">
                  <div class="content-txt-container">
                    <h3 class="title">'.ucwords($da['title']).'</h3>
                    <p>'.substr($da['desc'],0,20).'<a href="./course.php?cid='.$da['cid'].'">...see more</a></p>
                  </div>
                </div>
              </div>
            ';
          }
        ?>
        
        </div>   
    </div>
    <div class="nepre-wrapper">
      <ul>
        <li><a href="#"><i class="fa fa-arrow-left"></i></a></li>
        <li><a href="#">1</a></li>
        <li><a href="#">2</a></li>
        <li><a href="#">3</a></li>
        <li><a href="#"><i class="fa fa-arrow-right"></i></a></li>
      </ul>
    </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
</body>
</html>