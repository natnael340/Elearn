<?php
session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: login.php");
    }
    if(!isset($_GET['cid'])){
        header("Location: 404.html");
    }
    include("db.config.php");
    $data=view_course(intval($_GET['cid']),0);
    $config=include("config.php");
    if(!isset($data)){
        header("Location: 404.html");
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
<body>
<?php 
      $page=-1; #0 index.php & home.php, 1 courses.php, 3 cart.php, 4 mycourses.php
      include("components/header.php"); 
    ?>
    <div class="container course-container">
        <div class="col-md-4">
            <div class="thumb-cont">
                <img src="<?php echo $config['ctd'].$data['thumb'];?>" alt="course thumbnail">
            </div>
        </div>
        <div class="col-md-8">
            <h1><?php echo $data['title'];?></h1>
            <h4>Price: <?php echo $data['price'];?>$</h4>
            <span><?php echo $data['date'];?></span><br>
            <a href="./profile.php?uname=<?php echo $data['autor'];?>">@<?php echo $data['autor'];?></a>
        </div>
        
        <div class="col-md-12 desc">
            <br>
            <p><?php echo $data['desc'];?></p>
            <br>
            <br>
            <form action="cart.php" method="POST">
            <input type="hidden" value='<?php echo $data['cid'];?>' name="cid" >  
            
            <button class="btn btn-default cart" type="submit" name="cart">Cart</button>
            <button class="btn btn-default buy" type="submit" name="buy">Buy now</button>
            </form>
        </div>
        
    </div>
</body>
</html>