<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elearn</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="css/my.css" />
    <link rel="stylesheet" type="text/css" href="font-awesome-4.7.0/css/font-awesome.css" />
    <style>
  </style>
  </head>
  <body>
    <?php 
      $page=0; #0 index.php & home.php, 1 courses.php, 2 cart.php 
      include("components/header.php"); 
    ?>
    <div class="slider">
      <img src="images/single-gallery-01.png" />
      <div class="overlay">
        <h1>Get Any Course</h1>
        <h3>From Anywhere</h3>
        <a class="btn btn-default" href="signup.php">SIGN UP</a>
      </div>
    </div>
    <div class="container intro-container">
      <div class="row">
        <div class="col-md-6">
          <img src="images/single-gallery-01.png" alt="" class="responsive" width="100%">
        </div>
        <div class="col-md-6 ">
          <p class="intro">E-learning is an innovative approach for delivering electronically mediated,well-designed,learner-centered. interactive 
            learning enviroment by utilizing internt and digital technology with respect to instructional design principle.
          </p>
        </div>
      </div>
      <br>
      <div class="col-12">
        <h3>Most Viewd</h3>
        <hr>
        <div class="row">
        <?php 
          include("db.config.php");
          $config=include("config.php");
          $row=array(
            "i"=> 0,
            "f"=> 3,
          );
          $data=retrive_course($row,null,1,null,null);
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
    <br>
            </div>
            </div>
    <div class="container-fullss">
      &nbsp; 
      <div class="over">&nbsp;</div>
      <h1>Get Your Certeficate Online</h1>
    </div>
    <br>
    <br>
    <div class="container devops">
      <div class="col-md-4">
        <div class="devop-container">
          <div class="prof-container">
            <div class="img">
              &nbsp;
            </div>
          </div>
          <div class="pro-title">
            <h3>Natnael Tilahun</h3>
            <span>natnaeltilahun157@gmail.com</span>
            </div>
            <br>
            <p>Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="devop-container">
          <div class="prof-container">
            <div class="img">
              &nbsp;
            </div>
          </div>
          <div class="pro-title">
            <h3>Yoseph Mare</h3>
            <span>yosephmare716@gmail.com</span>
            </div>
            <br>
            <p>Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="devop-container">
          <div class="prof-container">
            <div class="img">
              &nbsp;
            </div>
          </div>
          <div class="pro-title">
            <h3>Yitbarek Shumet</h3>
            <span>yitbarekshumet481@gmail.com</span>
            </div>
            <br>
            <p>Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.</p>
        </div>
      </div>
      <div class="col-md-offset-2 col-md-4 myt-2">
        <div class="devop-container">
          <div class="prof-container">
            <div class="img">
              &nbsp;
            </div>
          </div>
          <div class="pro-title">
            <h3>Waleling Belstie</h3>
            <span>wlelingbeliste123@gmail.com</span>
            </div>
            <br>
            <p>Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.</p>
        </div>
            </div>
            <div class="col-md-4 myt-2">
        <div class="devop-container">
          <div class="prof-container">
            <div class="img">
              &nbsp;
            </div>
          </div>
          <div class="pro-title">
            <h3>Mikir Dessie</h3>
            <span>mikirdessie@gmail.com</span>
            </div>
            <br>
            <p>Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.Looking for props designer with experience using cardboard.</p>
        </div>
            </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
  </body>
</html>
