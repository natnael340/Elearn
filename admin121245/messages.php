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
    if(isset($_POST['chng_mode'])){
        
        if(isset($course[0])){
          edit_course($course[0],$course[1],$course[2],$course[3],$course[4],$course[5],$course[6],$course[8],intval($_POST['active']));
        }
      }
      $data=get_messages();
    
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elearn</title>
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/my.css" />
    <script src="../js/jquery.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    
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
        <h3>Courses</h3>
      <hr>
      <table>
          <tr>
              <th>Id</th>
              <th>Email</th>
              <th>Message</th>
              <th>Reply</th>
              <th>Read</th>
              <th>Date</th>
          </tr>
         <?php
         foreach($data as $da){
            echo '<tr>
            <td>'.$da['id'].'</td>
            <td>'.$da['email'].'</td>
            <td>'.$da['message'].'</td>
            <td>'.$da['reply'].'</td>
            <td><button class="btn btn-default '.(intval($da['read'])==0 ? 'btn-green':'btn-red').'" onclick="change_mod(this,'.$da['id'].','.intval($da['read']).')">'.(intval($da['read'])==0 ? 'Read':'Unread').'</button></td>
            <td>'.$da['date'].'</td>
            </tr>';
         } 
         ?>
      </table>
    </div>
    <script>
      var table=document.getElementsByTagName("table")[0];
      function fetchdata(sort, order){
        var d=table.children[0];
        table.innerHTML="";
        table.appendChild(d);
        var url="http://localhost/Elearn/admin121245/courses.php?courses=&sort="+sort+"&order="+order;
        var xhr=new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
        xhr.send();
        xhr.onload = function(){
          var data = JSON.parse(this.responseText);
          for(da in data){
            console.log(da);
            insert_data(data[da].cid, data[da].title, data[da].desc, data[da].category, data[da].price, data[da].view, data[da].thumb, data[da].private, data[da].active, data[da].autor, data[da].date);
          }
        }
      } 
      function insert_data(cid,title,desc,cat,price,view,thumb,pri,act,user,date){
        table.innerHTML+= `<tr>
                <td>${cid}</td>
                <td><a href="http://localhost/Elearn/admin121245/course.php?cid=${cid}" >${title}</a></td>
                <td>${desc}</td>
                <td>${cat}</td>
                <td>${price}</td>
                <td><a href='${thumb}' >img</a></td>
                <td>${view}</td>
                <td>${pri}</td>
                <td>${user}</td>
                <td><button class="btn btn-default ${act==0 ? 'btn-green':'btn-red'}" onclick="change_mod(this,${cid},${act})">${act==0 ? 'Activate':'Deactivate'}</button></td>
                <td>${date}</td>
                </tr>`
      }

          
      function change_mod(parent,cid,active){
        var act = active==1 ? 0:1;
        var req=`chng_mode=${cid}&active=${act}`;
        var url="http://localhost/Elearn/admin121245/courses.php";
        var xhr=new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
        xhr.send(req);
        xhr.onload = function(){
          if(act==0){
            parent.classList.replace("btn-red","btn-green");
            parent.innerText="Read";
          }
          else{
            parent.classList.replace("btn-green","btn-red");
            parent.innerText="Unread";
          }
         
        }

      }
    </script>-->
  </body>
</html>
