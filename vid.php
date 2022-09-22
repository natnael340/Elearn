<?php
    session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: login.php");
        exit();
    }
    if(!isset($_GET['cid'])){
        header("Location: 404.html");
        exit();
    }
    include("db.config.php");
    $config=include("config.php");
    if(!is_enrolled($_SESSION['uid'],$_GET['cid'])) {header("Location: 404.html");exit();}
    $videos = retrive_video($_GET['cid']);
    

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
    <div class="container">
        <div class="row">
            <div class="col-md-8 video-view-container">
            <video width="100%" height="100%" controls >
                <source src="#"  type="video/mp4">
            </video>
            <div class="vid-detail">
                <h1>Title</h1>
                <p>something is here look for others</p>
            </div>
            
            </div>
            <div class="col-md-4 list-vid">
               
         </div>
        </div>
    </div>
    <?php include("components/footer.php"); ?>
    <script language="javascript" src="js/index.js"></script>
    <script>
        var video=[
            <?php 
                foreach($videos as $vid){
                    echo "{
                        vid: ".$vid['vid'].
                        ", title: '".$vid['title'].
                        "', desc: '".$vid['desc'].
                        "', video: '".$config['vd'].$vid['video']."', },"; 
                }
            ?>
        ];
        var vid=document.getElementsByTagName("video")[0];
        changevid(video[0]['vid']);
        var container=document.getElementsByClassName("list-vid")[0];
        load_vid(video[0]['vid']);
        function createhtml(id, title, desc){
            var cont=`<div class="video-content-container">
                    <div class="vid-ico">
                        <i class="fa fa-play-circle fa-lg" onclick=changevid(${id})></i>
                    </div>
                    <div class="content-txt-container">
                        <h3 class="title">${title}</h3>
                        <p>${desc}<a href="#">...see more</a></p>
                     </div>
                </div><br>`;
                return cont;
        }
        function load_vid(id){
            container.innerHTML="";
            video.forEach(v => {
                        if(v['vid']!=id){
                            container.innerHTML+=createhtml(v['vid'], v['title'], v['desc']);
                        }
                        
                    })
        }
        function changevid(id){
            video.forEach(v => {if(v['vid']==id){ vid.children[0].src=v['video'];}});
            vid.load();
        }
        
    </script>
</body>
</html>