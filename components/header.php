<?php 
  if(!empty($error)){
    echo '<div class="error-popup">
    <span>'.$error.'</span>
</div>';
  }
?>
<header class="header fixed">
      <nav class="navbar navbar-style">
        <div class="container-fluid">
          <div class="navbar-header">
            <img src="images/book-icon.png" alt="Logo" class="logo" />
            <span style="font-size: 1em; color: black"><b>E-Learn</b></span>
          </div>
          <ul class="nav navbar-nav navbar-left nav-menu">
            <li><a href="home.php">HOME</a></li>
            <li class="cat"><a href="#">CATEGORY</a>
            <ul class="submenu">
              <li>
                <a href="category.php?ca=bum">Business & Management</a>
              </li>
              <li>
                <a href="category.php?ca=cam">Creative Arts & Media</a>
              </li>
              <li>
                <a href="category.php?ca=hcm">Healthcare & Medicine</a>
              </li>
              <li>
                <a href="category.php?ca=his">History</a>
              </li>
              <li>
                <a href="category.php?ca=ics">IT & Computer Science</a>
              </li>
              <li>
                <a href="category.php?ca=lan">Languge</a>
              </li>
              <li>
                <a href="category.php?ca=law">Law</a>
              </li>
              <li>
                <a href="category.php?ca=lit">Literature</a>
              </li>
              <li>
                <a href="category.php?ca=nae">Nature & Enviroment</a>
              </li>
              <li>
                <a href="category.php?ca=pos">Politics & Society</a>
              </li>
              <li>
                <a href="category.php?ca=psm">Psychology & Mental Health</a>
              </li>
              <li>
                <a href="category.php?ca=sem">Science, Engineering & Math</a>
              </li>
              <li>
                <a href="category.php?ca=sts">Study Skills</a>
              </li>
              <li>
                <a href="category.php?ca=tea">Teaching</a>
              </li>
              <li>
                <a href="category.php?ca=oth">Others</a>
              </li>
            </ul>
            </li>
            <li><a href="./cart.php">CART</a></li>
            <li><a href="#"><i class="fa fa-bell"></i></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right nav-menu">
          <?php 
            if(isset($_SESSION['username'])){ 
                echo "<li><a href='profile.php'>".$_SESSION['username']."</a></li>\n<li><a href=\"logout.php\">Logout</a></li>"; 
              }
              else {
                echo "<li><a href=\"login.php\">LOGIN</a></li>\n<li><a href=\"signup.php\">SIGNUP</a></li>";
              } 
              ?>
              <li class="search-wrapper"><input type="text" name="search" onchange="searchinp(this)" oninput="this.onchange()" onkeyup="this.onchange()" onpaste="this.onchange()"><a id=sea href="#"><i class="fa fa-search search-ico"></i></a></li>
          </ul>
        </div>
      </nav>
      <script language="javascript">
        if(<?php echo $page ?>!=-1){
          document.getElementsByClassName("nav-menu")[0].children[<?php echo $page ?>].children[0].classList.add("active");
        }
      </script>
</header>