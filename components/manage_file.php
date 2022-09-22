<?php
    $error;
    $config=include("config.php");
    function uploadfile(){
        global $error, $config;
        $target_dir = $config['ctd'];
        $name = round(microtime(true)*1000);
        $temp=explode(".",$_FILES['thumbnail']['name']);
        $f_name=$name.".".strtolower($temp[count($temp)-1]);
        $target_file = $target_dir.$f_name;
        $uploadOk = 1;
        $imageFileType=strtolower($temp[count($temp)-1]);
        $error="";
        $check=getimagesize($_FILES['thumbnail']["tmp_name"]);
        if($check!=false){
            $uploadOk=1;
        }
        else{
            $uploadOk=0;
            $error="ERROR: File Not an image";
        }
        if($_FILES['thumbnail']['size'] > 2000000){
            $uploadOk=0;
            $error="ERROR: File is too large";
        }
        if($imageFileType != "png" && $imageFileType != "jpg" && $imageFileType != "jpeg"){
            $uploadOk=0;
            $error="ERROR: File must be in jpg,jpeg or png format";
        }
        if($uploadOk == 0){
            unset($f_name);
        }
        else{
            if(move_uploaded_file($_FILES['thumbnail']['tmp_name'],$target_file)){
                echo "File Uploaded";
            }
            else{
                unset($f_name);

            }
        }
        return $f_name;
    }
    function vidupload(){
        global $error, $config;
        $target_dir = $config['vd'];
        $name = round(microtime(true)*1000);
        $temp=explode(".",$_FILES['video']['name']);
        $f_name=$name.".".strtolower($temp[count($temp)-1]);
        $target_file = "videos/".$f_name;
        $uploadOk = 1;
        $imageFileType=strtolower($temp[count($temp)-1]);
        $error="";
        $ext=array(
            "mp4",
            "webm",
        );
        if(!in_array($imageFileType, $ext)){
            $error="ERROR: Video Extension must be mp4 or webm.";
            $uploadOk=0;
        }
        if($_FILES['video']['size']>1000000000){
            $error="ERROR: File to large.";
            $uploadOk=0;
        }
        if($uploadOk==0){
            unset($f_name);
        }
        else{
            if(!move_uploaded_file($_FILES['video']['tmp_name'],$target_file)){
                unset($f_name);
            }
        }
        return $f_name;
    }
?>