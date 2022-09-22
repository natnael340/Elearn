<?php
    if(!isset($_GET['ca'])){
        header("Location: category.php?ca=bussiness");
        exit();
    }
    $config=include("config.php");
    $category = $config['category'];
    if(!isset($category[$_GET['ca']])){
        header("Location: home.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/my.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css" />
    <title>Categrory</title>
</head>
<body>
    <?php 
        $page=1;
        include("components/header.php");
    ?>
    <div class="container category">
      <div class="col-12">
        <h3><?php echo $category[$_GET['ca']];?></h3>
        <hr>
        <div class="row">
        <?php 
          include("db.config.php");
          $config=include("config.php");
          $row=array(
            "i"=> 0,
            "f"=> 20,
          );
          $data=retrive_course($row,4,null,$_GET['ca'],null);
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