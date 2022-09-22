<?php
session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: login.php");
    }
    include("components/manage_file.php");
    include("db.config.php");
    $config=include("config.php");
    unset($error);
    if(isset($_GET['data'])){
        $cid=$_GET['data'];
        $course=view_course($cid,1);
        if(isset($error) or $course['autorid']!=$_SESSION['uid']){
            $error="ERROR: No such Course with this id.";
            unset($cid);
        }
        
    }
    
    elseif(isset($_POST['next'])){
        $user=view_profile($_SESSION['uid'],null);
        if(!isset($user['card_num'])){
            $err="error";
            $expiree= time() + (1);
            setcookie($err, "ERROR: You must first manage Payment Method", $expiree);
            header("Location: profile.php");
            exit();
        }
        if(!empty($_POST['title']) && isset($_POST['category']) && isset($_POST['price']) && isset($_FILES['thumbnail'])){
            $name=uploadfile();
            $desc = isset($_POST['description']) ? $_POST['description']:"";
            if(isset($name)){
                $private=isset($_POST['private']) ? ($_POST['private']=="on" ? 1:0) :0 ;
                $courseId = create_course($_POST['title'],$desc,$_POST['category'],$_POST['price'],$name,$private,$_SESSION['uid']);
                if(isset($courseId)){
                    $_SESSION['cid']=$courseId;
                    header("Location: uploadVideos.php");
                }
                
            }
            
        }
        else{
             $error="ERROR: Fill all the fields.";
        }
        
        
    }

?><!DOCTYPE html>
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
    <div class="container course-upload-container">
        <div class="col-md-offset-3 col-md-6">
            <form method="POST" enctype="multipart/form-data">
                <label for="title" class="title">Title</label>
                <input type="text" name="title" value="<?php echo isset($cid) ? ucwords($course['title']):''?>">
                <br>
                <label for="desc" class="description">Description</label>
                <textarea name="description"><?php echo isset($cid) ? $course['desc']:''?></textarea>
                <br>
                <label class="category">Category</label>
                <select name="category">
                    <option></option>
                    <?php 
                        $ca = $config['category'];
                        foreach($ca as $key => $value){
                            echo '<option value="'.$key.'" '.(isset($cid) ? ($course['cat']==$key ? 'selected':''):'').' >'.$value.'</option>';
                        }
                    ?>
                </select>
                <br>
                <label for="price" >Price</label>
                <input type="number" name="price" min="0.00" max="5000.00" value="<?php echo isset($cid) ? $course['price']:'0.0'?>">
                <br>
                <input type="checkbox" name="private" <?php echo isset($cid) ? 'checked':''?>>&nbsp;
                <label for="price" >Private</label><br>
                <span>By selecting this checkbox no one except you can view your course.</span>
                <br>
                <label for="">Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/jpg,image/png,image/jpeg" style="display: none" onchange="imgs(this);">
                <div class="thumbnail-view" onclick="upload(this);">
                    <img src="<?php echo isset($cid) ? $config['ctd'].$course['thumb']:'#'; ?>" alt="" style="display: block;">
                    <h1>Upload your thumbnail</h1>
                    <i class="fa fa-lg fa-plus"></i>
                </div>
                <?php echo !isset($cid) ? '<input type="submit" value="Next" class="btn btn-default" name="next">':'<input type="submit" value="Save" class="btn btn-default" name="edit">';?>
            </form>
        </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
    <script language="javascript">
        function imgs(img) {
            var parent = document.getElementsByClassName("thumbnail-view")[0];
            if(img.files && img.files[0]){
                var reader = new FileReader();
                reader.onload = function (e) {
                    parent.children[0].setAttribute("src",e.target.result);
                    parent.children[0].style.display="block";
                    parent.children[1].style.display="none";
                    parent.children[2].style.display="none";
                }
                reader.readAsDataURL(img.files[0]);
            }
        }
        function upload(parent) {
            var img = document.getElementsByName("thumbnail")[0];
            img.click();
        }
        
    </script>
</body>
</html>