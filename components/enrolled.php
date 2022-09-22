<div class="header">
            <h3>Enrolled Course</h3>
        </div>
        <hr>
        <?php 
        foreach($enrolled as $data){
            echo '
        <div class="col-md-4">
          <div class="content-container">
            <img src="'.$config['ctd'].$data['thumb'].'" alt="">
            <div class="content-txt-container">
              <h3 class="title">'.ucwords($data['title']).'</i></h3>
              <p>'.substr($data['desc'],0,20).'<a href="./vid.php?cid='.$data['cid'].'">...see more</a></p>
            </div>
            <br>
            <br>
            <div class="touchable ">
            <form method="POST" >
                <input type="hidden" value="'.$data['cid'].'" name="cid" >
                <button class="button btn btn-default del" type="submit" name="del_enrolled">
                    <i class="fa fa-trash"></i>
                </button>
                </form>
            </div>
          </div>
        </div>  
        ';
        }      
        ?>