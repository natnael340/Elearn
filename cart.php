<?php
    session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: login.php");
    }
    
    include("db.config.php");
    $config=include("config.php");
    if(isset($_POST['cart'])){
        if(isset($_POST['cid'])){
            add_cart($_SESSION['uid'], $_POST['cid']);
            echo $error;
        }
        else{
            echo "course id not set";
            exit();
        }
    }
    if(isset($_POST['delete'])){
        delete_cart($_SESSION['uid'],$_POST['delete']);
        echo "OK";
        exit();
    }
    include("buycourse.php");
    if(isset($_POST['buy'])){
        buy($_POST['cid']);
        header("Location: profile.php");
        exit();

    }
    $data=view_cart($_SESSION['uid']);
    $total=0;
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
      $page=2; #0 index.php & home.php, 1 courses.php, 2 cart.php
      include("components/header.php"); 
    ?>
    <script language="javascript" src="js/index.js"></script>
    <div class="container course-container">
        <?php 
            
            if(!isset($error)){
                foreach($data as $da){
                    echo '
                    <div class="row" id="row-'.$da['cid'].'">
                    <div class="col-md-4">
                        <div class="thumb-cont">
                            <img src="'.$config['ctd'].$da['thumb'].'" alt="course thumbnail">
                        </div>
                    </div>
                    <div class="col-md-7">
                    <input type="hidden" value='.$da['cid'].' name="cid-'.$da['cid'].'" >
                    <input type="hidden" value='.$da['price'].' name="price-'.$da['cid'].'" >
                        <a href="./course.php?cid='.$da['cid'].'"><h1>'.ucwords($da['title']).'</h1></a>
                        <h4>Price: '.$da['price'].'$</h4>
                        <span>'.$da['date'].'</span><br>
                        <a href="./profile.php?user='.$da['autor'].'">@'.$da['autor'].'</a>
                    </div>
                    <div class="col-md-1">
                        <i class="fa fa-remove fa-lg" onclick="deleteCourse('.$da['cid'].');" ></i>
                    </div>
                    </div>';
                    $total+=doubleval($da['price']);
                }
            }
        ?>
        <br>
        <hr>
        <div class="totp">
            <span class="tot">Total</span>
            <span class="price"><?php echo $total ?>$</span>
            <br>
            <br>
            <br>
            <br>
            <button class="btn btn-default">Proceed to Check Out</button>
        </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
</body>
</html>