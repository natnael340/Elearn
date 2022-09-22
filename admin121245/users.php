<?php
    session_start();
    if(!isset($_SESSION['uid'])){
        header("Location: ../login.php");
        exit();
    }
    if(intval($_SESSION['admin'])!=1){
      header("Location: index.php");
      exit();
  }
    include("../db.config.php");
    if(isset($_GET['users'])){
      $data=retrive_users_admin(intval($_GET['sort']),intval($_GET['order']));
      echo json_encode($data);
      exit();
    }
    if(isset($_POST['chng_mode'])){
      $user = mysqli_query($conn,"SELECT * from users where id=".intval($_POST['chng_mode']));
      $user=mysqli_fetch_assoc($user);
      if($_POST['state']<=1)
      $query="UPDATE users SET active=".$_POST['state']." WHERE id=".intval($_POST['chng_mode']);
      elseif($_POST['state']<=3)
      $query="UPDATE users SET staff=".(intval($_POST['state'])-2)." WHERE id=".intval($_POST['chng_mode']);
      else
      $query=$_POST['state']==4 ? "UPDATE users SET admin=0, staff=1 WHERE id=".intval($_POST['chng_mode']):"UPDATE users SET admin=1, staff=1 WHERE id=".intval($_POST['chng_mode']);
      echo $query;
      mysqli_query($conn,$query);
      exit();
      }
    
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
        <h3>Users</h3>
        <div class="dropdown"> 
          <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
            Sort
            <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            <li><a onclick="fetchdata(1,0)">Id <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(1,1)">Id <i class="fa fa-arrow-up"></i></a></li>
            <li><a onclick="fetchdata(2,0)">Username <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(2,1)">Username <i class="fa fa-arrow-up"></i></a></li>
            <li><a onclick="fetchdata(3,0)">Email <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(3,1)">Email <i class="fa fa-arrow-up"></i></a></li>
            <li><a onclick="fetchdata(5,0)">Active <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(5,1)">Active <i class="fa fa-arrow-up"></i></a></li>
            <li><a onclick="fetchdata(6,0)">Staff <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(6,1)">Staff <i class="fa fa-arrow-up"></i></a></li>
            <li><a onclick="fetchdata(7,0)">Admin <i class="fa fa-arrow-down"></i></a></li>
            <li><a onclick="fetchdata(7,1)">Admin <i class="fa fa-arrow-up"></i></a></li>
          </ul>
        </div>
      <hr>
      <table>
          <tr>
              <th>Id</th>
              <th>Username</th>
              <th>Email</th>
              <th>Bio</th>
              <th>Active</th>
              <th>Staff</th>
              <th>Admin</th>
          </tr>
         
      </table>
    </div>
    <script>
      var table=document.getElementsByTagName("table")[0];
      function fetchdata(sort, order){
        var d=table.children[0];
        table.innerHTML="";
        table.appendChild(d);
        var url="http://localhost/Elearn/admin121245/users.php?users=&sort="+sort+"&order="+order;
        var xhr=new XMLHttpRequest();
        xhr.open("GET", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
        xhr.send();
        xhr.onload = function(){
          var data = JSON.parse(this.responseText);
          for(da in data){
            insert_data(data[da].id, data[da].uname, data[da].email, data[da].bio, data[da].active, data[da].staff, data[da].admin);
          }
        }
      } 
      function insert_data(id,uname,email,bio,active,staff,admin){
        table.innerHTML+= `<tr>
                <td>${id}</td>
                <td><a href="http://localhost/Elearn/profile.php?uname=${uname}" >${uname}</a></td>
                <td>${email}</td>
                <td>${bio}</td>
                <td><button class="btn btn-default ${active==0 ? 'btn-green':'btn-red'}" onclick="change_mod(this,${id},${active})">${active==0 ? 'Activate':'Deactivate'}</button></td>
                <td><button class="btn btn-default ${staff==0 ? 'btn-green':'btn-red'}" onclick="change_mod(this,${id},${staff+2})">${staff==0 ? 'Staff':'User'}</button></td>
                <td><button class="btn btn-default ${admin==0 ? 'btn-green':'btn-red'}" onclick="change_mod(this,${id},${admin+4})">${admin==0 ? 'Admin':'Staff'}</button></td>
                </tr>`
      }

         fetchdata(1,0);   
      function change_mod(parent,id,active){
        var act= active==0 || active==2 || active==4 ? active+1:active-1;
        var req=`chng_mode=${id}&state=${act}`;
        var url="http://localhost/Elearn/admin121245/users.php";
        var xhr=new XMLHttpRequest();
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8")
        xhr.send(req);
        xhr.onload = function(){
          console.log(this.responseText);
          if(act<=1){
            if(act==0){
              parent.classList.replace("btn-red","btn-green");
              parent.innerHTML="Activate";
            }
            else{
              parent.classList.replace("btn-green","btn-red");
              parent.innerHTML="Deactivate";
            }
          }
          else if(act<=3){
            if(act==2){
              parent.classList.replace("btn-red","btn-green");
              parent.innerHTML="Staff";
            }
            else{
              parent.classList.replace("btn-green","btn-red");
              parent.innerHTML="User";
            }
          }
          else{
            if(act==4){
              parent.classList.replace("btn-red","btn-green");
              parent.innerHTML="Admin";
            }
            else{
              parent.classList.replace("btn-green","btn-red");
              parent.innerHTML="Staff";
            }
          }
          
        }

      }
    </script>
  </body>
</html>
