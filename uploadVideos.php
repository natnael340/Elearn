<?php
session_start();
    include("components/manage_file.php");
    include("db.config.php");
    if(!isset($_SESSION['cid'])){
        header("Location: uploadCourse.php");
        exit();
    }
    if(isset($_POST['finish']) or isset($_POST['next'])){
        if(!empty($_POST['vtitle'])&& isset($_FILES['video'])){
            $name=vidupload();
            $desc = isset($_POST['description']) ? $_POST['description']:"";
            if(isset($name)){
                add_video($_POST['vtitle'],$desc,$name,$_SESSION['cid']);

            }
        }
        else{
            $error="ERROR: All fields must be filled.";
        }
        
    if(isset($_POST['finish'])){
        header("Location: profile.php");
        exit();
    } 
        
        
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
    <div class="container video-upload-container">
        <div class="row">
            <div class="col-md-8">
                <div class="video-container">
                    <video width="100%" height="100%" autoplay controls >
                        <source src="#" type="video/mp4">
                    </video>
                    <h1>Upload your Video</h1>
                    <i class="fa fa-lg fa-plus" onclick="upload()"></i>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <label for="vtitle">Video title</label>
                    <input type="text" name="vtitle" >
                    <br>
                    <label for="desc" class="description">Description</label>
                    <textarea name="description" value=""></textarea>
                    <input type="file" name="video" accept="video/*" style="display: none" onchange="vids(this)">
                    <br>
                    <br>
                    <input type="submit" value="Next" class="btn btn-default" name="next">
                    <input type="submit" value="Upload Course" name="finish" class="btn btn-default">
                </form>
                
            </div>
            
            <div class="col-md-4">
                <?php
                $video=retrive_video($_SESSION['cid']);
                if(isset($video)){
                    $video=array_reverse($video);
                    foreach($video as $vid){
                        echo '
                            <div class="video-content-container">
                                <div class="vid-ico">
                                    <i class="fa fa-play-circle fa-lg"></i>
                                </div>
                                <div class="content-txt-container">
                                    <h3 class="title">'.ucwords($vid['title']).'</h3>
                                    <p>'.substr($vid['desc'], 0, 100).'<a href="#">...see more</a></p>
                                </div>
                            </div><br>';
                    }
                
                }
                ?>
            </div>

        </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript">
        function vids(vid) {
            var parent = document.getElementsByClassName("video-container")[0];
            
            if(vid.files && vid.files[0]){
                
                var reader = new FileReader();
                reader.onload = function (e) {
                    parent.children[0].children[0].setAttribute("src",e.target.result);
                    parent.children[0].style.display="block";
                    parent.style.color="#000";
                    parent.children[1].style.display="none";
                    parent.children[2].style.display="none";
                    parent.children[0].load();
                }
                reader.readAsDataURL(vid.files[0]);
            }
        }
        function upload() {
            var vid = document.getElementsByName("video")[0];
            vid.click();
        }
        
    </script>
</body>
</html>