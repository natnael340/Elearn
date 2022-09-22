<?php
    session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: ../login.php");
    }
    if(!isset($_SESSION['admin']) or !isset($_SESSION['staff'])){
        header("Location: ../404.html");
        exit();
    }
    include("../db.config.php");
    if(!isset($_GET['cid'])){
        echo "No ID supplied";
        exit();
    }
    $videos=retrive_video(intval($_GET['cid']));
    $config=include("../config.php");
    $course = mysqli_query($conn,"SELECT title from courses where id=".intval($_GET['cid']));
    $course=mysqli_fetch_row($course);
    
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
      <h3><?php echo $course[0];?>'s Videos</h3>
      <hr>
      <table>
          <tr>
              <th>Id</th>
              <th>Title</th>
              <th>Description</th>
              <th>Video</th>
          </tr>
         <?php
            foreach($videos as $vid){
                echo ' <tr>
                <td>'.$vid['vid'].'</td>
                <td>'.$vid['title'].'</td>
                <td>'.$vid['desc'].'</td>
                <td><a href="http://localhost/Elearn/'.$config['vd'].$vid['video'].'" >'.$vid['video'].'</a></td>
                </tr>';
            }
        ?>
      </table>
    </div>
  </body>
</html>
