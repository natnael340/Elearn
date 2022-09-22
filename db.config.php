<?php
    $db_="localhost";
    $db_name="Elearn";
    $db_username="root";
    $db_password="";
    $conn = mysqli_connect($db_,$db_username,$db_password, $db_name);
    $error;
    $config=include_once("config.php");


    function validate_user($username, $password){
        global $conn;
        $query="SELECT username, id, staff, admin FROM users WHERE username = ? AND password = ? AND active != 0;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ss', $username, md5($password));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $uname,$uid,$staff, $admin);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return ["uname"=>$uname,"uid"=>$uid, "staff"=>$staff, "admin"=>$admin];
    }
    function check_user($username){
        global $conn;
        $query1="SELECT id FROM users WHERE username = ?;";
        $stmt1=mysqli_prepare($conn, $query1) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt1, 's', $username);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_bind_result($stmt1, $id);
        mysqli_stmt_fetch($stmt1);
        mysqli_stmt_close($stmt1);
        return $id;
    }
    function register_user($username, $email, $password, $active, $staff){
        global $conn,$error;
        if(!empty(check_user($username))){
            $error="ERROR: Username exists.";
            return null;
        }
        $query="INSERT INTO users(username,email,password,active,staff) VALUES (?,?,?,?,?);";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'sssii', $username,$email, md5($password),intval($active), intval($staff));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $id=check_user($username);
        if(isset($id)){
            unset($error);
            return $id;
        }
        $error="ERROR: Failed to add user";
        return $id;

    }

    function create_course($title, $desc, $category, $price, $thumbnail, $private, $autorid){
        global $conn;
        $query="INSERT INTO courses(title,description,category,price,thumbnail,uploadDate,private,autorid,active) VALUES (?,?,?,?,?,now(),?,?,0);";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'sssdsii', $title, $desc, $category, $price, $thumbnail,intval($private), $autorid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $queryid="SELECT id from courses WHERE thumbnail = ?;";
        $stmt1=mysqli_prepare($conn, $queryid) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt1, "s", $thumbnail);
        mysqli_stmt_execute($stmt1);
        mysqli_stmt_bind_result($stmt1, $cid);
        mysqli_stmt_fetch($stmt1);
        mysqli_stmt_close($stmt1);

        return $cid;
    }
    function retrive_course($rows, $uid, $order,$cat,$s){
        global $conn;
        $data=array();
        if(isset($uid)){
            $query="SELECT id, title, description, thumbnail, private, active, view FROM courses WHERE autorid = $uid ORDER BY uploadDate LIMIT ?,?";
        }
        elseif(!isset($order)){
            $query="SELECT id, title, description, thumbnail, private, active, view FROM courses WHERE active !=0 AND private !=1 ORDER BY uploadDate LIMIT ?,?";
        }
        else{
            $query="SELECT id, title, description, thumbnail, private, active, view FROM courses WHERE active !=0 AND private !=1 ORDER BY view DESC LIMIT ?,?";
        }
        if(isset($cat)){
            $query="SELECT id, title, description, thumbnail, private, active, view FROM courses WHERE active !=0 AND private !=1 AND category = '$cat' ORDER BY view DESC LIMIT ?,?";
        }
        if(isset($s)){
            $query="SELECT id, title, description, thumbnail, private, active, view FROM courses WHERE active !=0 AND private !=1 AND title LIKE ? OR description LIKE ? ORDER BY view DESC LIMIT ?,?";
            $s='%'.$s.'%';
        }
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        isset($s) ? mysqli_stmt_bind_param($stmt, 'ssii',$s,$s, $rows['i'],$rows['f']):mysqli_stmt_bind_param($stmt, 'ii', $rows['i'],$rows['f']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid,$title,$desc,$thumbnail,$private,$active,$view);
        while(mysqli_stmt_fetch($stmt)){
            $data[$cid]=array(
                "cid" => $cid,
                "title" => $title,
                "desc" => $desc,
                "thumb" => $thumbnail,
                "private" => $private,
                "active" => $active,
                "view" => $view,
            );
        }
        mysqli_stmt_close($stmt);
        return $data;
        
    }
    function edit_course($id,$title,$desc,$cat,$pri,$thumb,$view,$priv,$active){
        global $conn, $error;
        $query="UPDATE courses SET title=?, description=?, category=?, price=?, thumbnail=?, view=?, private=?, active=? WHERE id=?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'sssdsiiii', $title, $desc, $cat, $pri,$thumb,$view,$priv, $active, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function add_video($title, $desc, $video, $courseid){
        global $conn;
        $query="INSERT INTO videos(title,description, video, courseId) VALUES (?,?,?,?);";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'sssi', $title, $desc, $video, $courseid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function retrive_video($cid){
        global $conn;
        $data=array();
        $query="SELECT id, title, description, video FROM videos WHERE courseId = ? ORDER BY id";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $vid,$title,$desc,$video);
        while(mysqli_stmt_fetch($stmt)){
            $data[$vid]=array(
                "vid" => $vid,
                "title" => $title,
                "desc" => $desc,
                "video" => $video,
            );
        }
        mysqli_stmt_close($stmt);
        return $data;
        
    }
    function view_course($cid, $private){
        global $error, $conn;
        $query="SELECT courses.id, courses.title, courses.description, courses.category, courses.thumbnail, courses.price, courses.private, courses.active, courses.view, courses.uploadDate, users.username, users.id FROM courses INNER JOIN users ON courses.autorid = users.id WHERE courses.id = ?;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $desc, $category, $thumbnail, $price, $pri, $act, $view, $date, $autor, $autorid);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if(!isset($id)){
            $error="ERROR: No such Course with this id";
            return null;
        }
        if(intval($act)!=1){
            if(intval($autorid)!=$_SESSION['uid']){
                $error="ERROR: No such Course with this id.";
                return null;
            }
        }
        if(intval($pri)!=0){
            if($private!=1){
                if(intval($autorid)!=$_SESSION['uid']){
                    $error="ERROR: No such Course with this id.";
                    return null;
                }
            }
        }
        return array(
            "cid" => $id,
            "title" => $title,
            "desc" => $desc,
            "thumb" => $thumbnail,
            "cat" => $category,
            "price" => $price,
            "private" => $pri,
            "active" => $act,
            "view" => $view,
            "date" => $date,
            "autor" => $autor,
            "autorid"=> $autorid
        );


    }
    function check_cart($uid,$cid){
        global $conn,$error;
        $query="SELECT cid FROM cart WHERE uid = ? AND cid = ?;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $id;
    }
    function add_cart($uid, $cid){
        global $conn,$error;
        if(!empty(check_cart($uid,$cid))){
            $error="ERROR: Course exist in your cart";
            return;
        }
        $query="INSERT INTO cart(uid, cid) VALUES (?,?);";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        unset($error);
    }
    function view_cart($uid){
        global $conn, $error;
        $data=array();
        $cids=array();
        $i=0;
        $query="SELECT cid FROM cart WHERE uid = ?;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid);
        
        while(mysqli_stmt_fetch($stmt)){
            $cids[$i]=$cid;
            $i++;
        }
        mysqli_stmt_close($stmt);
        if($i==0){
            $error="INFO: No course is added to your cart yet";
        }
        else{
            unset($error);
            foreach($cids as $id){
                $data[$id]=view_course($id,1);
            }
        }
        return $data;
    }
    function delete_cart($uid, $cid){
        global $conn, $error;
        $query="DELETE FROM cart WHERE uid=? AND cid=?;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_execute($stmt);
    }
    function view_profile($uid, $uname){
        global $conn, $error;
        $data = array();
        if(isset($uname)){
            $query="SELECT
            users.id,
            users.username,
            users.email,
            users.bio,
            COUNT(enrolled_courses.uid)
            FROM
            users,
            enrolled_courses
            WHERE
            users.username = ? AND enrolled_courses.uid = users.id;";
            $query_view="SELECT
            COUNT(courses.id),
            SUM(courses.view)
            FROM
            courses
            INNER JOIN users ON users.id = courses.autorid
            WHERE
            users.username = ?;";
        }
        else{
            $query="SELECT
            users.id,
            users.username,
            users.email,
            users.bio,
            billing.full_name,
            billing.cvv,
            billing.expDate,
            billing.CardNum,
            billing.balance,
            COUNT(
                DISTINCT enrolled_courses.uid,
                enrolled_courses.cid
            )
            FROM
            users
            LEFT JOIN billing ON users.id = billing.id,
            enrolled_courses
            WHERE
            users.id = ? AND enrolled_courses.uid = users.id;";
            $query_view="SELECT
            COUNT(courses.id),
            SUM(courses.view)
            FROM
            courses
            WHERE
            courses.autorid = ?;";
        }
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        isset($uname) ? mysqli_stmt_bind_param($stmt, 's', $uname):mysqli_stmt_bind_param($stmt, 'i', $uid);
        mysqli_stmt_execute($stmt);
        isset($uname) ? mysqli_stmt_bind_result($stmt, $id,$unames,$email,$bio, $ec): mysqli_stmt_bind_result($stmt, $id, $unames,$email,$bio, $bfull_n, $bcvv, $bexpdate, $bcardnum, $bbalance, $ec);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $stmt=mysqli_prepare($conn, $query_view) or die (mysqli_error($conn));
        isset($uname) ? mysqli_stmt_bind_param($stmt, 's', $uname):mysqli_stmt_bind_param($stmt, 'i', $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $num_co,$tot_view);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if(isset($uname)){
            $data = array(
                "id" => $id,
                "uname" => $unames,
                "email" => $email,
                "bio" => $bio,
                "ec" => $ec,
                "uc" => $num_co,
                "view" => $tot_view,
            );
            return $data;
        }
        
        $data = array(
            "id" => $id,
            "uname" => $unames,
            "email" => $email,
            "bio" => $bio,
            "ec" => $ec,
            "uc" => $num_co,
            "view" => $tot_view,
            "fname" => $bfull_n,
            "card_num" => $bcardnum,
            "cvv" => $bcvv,
            "exp" => $bexpdate,
            "balance" => $bbalance,
        );
        return $data;
    }
    function buy_course($uid, $cid){
        global $conn, $error;
        $query="INSERT INTO enrolled_courses(uid,cid) VALUES (?,?)";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function retrive_enrolled_course($uid){
        global $conn, $error;
        $data = array();
        $cids=array();
        $i=0;
        $query="SELECT cid FROM enrolled_courses WHERE uid=?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'i', $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $cid);
        

        while(mysqli_stmt_fetch($stmt)){
            $cids[$i]=$cid;
            $i++;
        }
        mysqli_stmt_close($stmt);
        if($i!=0){
            unset($error);
            foreach($cids as $id){
                $data[$id]=view_course($id,1);
            }
        }
        return $data;
    }
    function is_enrolled($uid,$cid){
        global $conn;
        $query="SELECT cid FROM enrolled_courses WHERE uid=? and cid=?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        if(isset($id)) return true;
        return false;
    }
    function del_enrolled_course($uid, $cid){
        global $conn, $error;
        $query="DELETE FROM enrolled_courses WHERE uid=? AND cid=?;";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'ii', $uid,$cid);
        mysqli_stmt_execute($stmt);
    }
    function update_balance($uid, $balance){
        global $conn, $error;
        $query="UPDATE billing SET balance=? WHERE id=?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'di', $balance, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function lock_unlock($uid, $cid,$lock){
        global $conn;
        $query="UPDATE courses SET private=? WHERE id=? AND autorid=?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, 'iii', $lock, $cid, $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    //Admin
    function retrive_user_biiling(){
        global $conn;
        $data=array();
        $i=0;
        $query="SELECT
        users.id,
        users.username,
        billing.full_name,
        billing.cvv,
        billing.expDate,
        billing.CardNum,
        billing.balance
        FROM
        users
        LEFT JOIN billing ON users.id = billing.id";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $uid, $uname, $fname,$cvv,$expdate, $cardnum, $balance);
        
        while(mysqli_stmt_fetch($stmt)){
            $data[$i]=array(
                "uid" => $uid,
                "uname" => $uname,
                "fname" => $fname,
                "cvv" => $cvv,
                "exp" => $expdate,
                "cardnum" => $cardnum,
                "balance" => $balance,
            );
            $i++;
        }
        mysqli_stmt_close($stmt);

        return $data;

    }
    //Admin
    function retrive_pending_course(){
        global $conn;
        $data=array();
        $i=0;
        $query="SELECT courses.id, courses.title, courses.category, courses.description, courses.thumbnail, courses.price, courses.private, courses.active, courses.view, users.username FROM courses INNER JOIN users ON courses.autorid = users.id ORDER BY courses.uploadDate; ";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $desc, $category, $thumbnail, $price, $pri, $act, $view, $autor);
        
        while(mysqli_stmt_fetch($stmt)){
            $data[$i]=array(
                "cid" => $id,
                "title" => $title,
                "desc" => $desc,
                "category" => $category,
                "thumb" => $thumbnail,
                "price" => $price,
                "private" => $pri,
                "active" => $act,
                "view" => $view,
                "autor" => $autor,
            );
            $i++;
        }
        mysqli_stmt_close($stmt);

        return $data;
    }
    //User
    function modify_payement_info($uid, $chn, $cn, $exp, $cvv){
        global $conn;
        $query="SELECT id FROM billing WHERE id = ? ";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "i", $uid);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        if(isset($id)){
            $query="UPDATE billing SET full_name = ?, cvv = ?, expDate = ?, cardNum = ? WHERE id = ? ";
            $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "ssssi",$chn,$cvv,$exp,$cn,$uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
        else{
            $query="INSERT INTO billing(id,full_name,cvv,expDate,cardNum) VALUES (?,?,?,?,?);";
            $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
            mysqli_stmt_bind_param($stmt, "issss",$uid,$chn,$cvv,$exp,$cn);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt); 
        }
    }
    //Admin
    function retrive_course_admin($sort, $order){
        global $conn, $config;
        $data=array();
        $query=($order==1 ? "SELECT * FROM courses ORDER BY ? DESC" : "SELECT * FROM courses ORDER BY ?");
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "i",$sort);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $title, $desc, $category, $price, $thumbnail, $view, $date, $pri, $act, $autor);

        while(mysqli_stmt_fetch($stmt)){
            array_push($data,array(
                "cid" => $id,
                "title" => $title,
                "desc" => $desc,
                "category" => $category,
                "thumb" => "http://localhost/Elearn/".$config['ctd'].$thumbnail,
                "price" => $price,
                "private" => $pri,
                "active" => $act,
                "view" => $view,
                "date" => $date,
                "autor" => $autor,
            ));
        }
        mysqli_stmt_close($stmt);

        return $data;

    }
    //Admin
    function retrive_users_admin($sort, $order){
        global $conn, $config;
        $data=array();
        $query=($order==1 ? "SELECT id, username, email, bio, active, staff, admin FROM users ORDER BY ? DESC" : "SELECT id, username, email, bio, active, staff, admin FROM users ORDER BY ?");
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "i",$sort);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $uname, $email, $bio, $active, $staff, $admin);

        while(mysqli_stmt_fetch($stmt)){
            array_push($data,array(
                "id" => $id,
                "uname" => $uname,
                "email" => $email,
                "bio" => $bio,
                "active" => $active,
                "staff" => $staff,
                "admin" => $admin,
            ));
        }
        mysqli_stmt_close($stmt);

        return $data;

    }
    function edit_user($id,$email,$bio,$password){
        global $conn, $error;
        $query = empty($password) ? "UPDATE users SET email = ?, bio = ? WHERE id = ?":"UPDATE users SET email = ?, bio = ?, password = ? WHERE id = ?";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        $pass=md5($password);   
        empty($password) ? mysqli_stmt_bind_param($stmt, "ssi",$email,$bio,$id):mysqli_stmt_bind_param($stmt, "sssi",$email,$bio,$pass,$id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function set_message($email, $message){
        global $conn;
        $query="INSERT INTO contact_message(email,message,date) VALUES (?,?,now());";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "ss",$email,$message);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt); 
    }
    function update_message($id, $replay, $read){
        global $conn;
        $query="UPDATE contact_message SET reply = ?, mread = ? WHERE id = ? ";
        $stmt=mysqli_prepare($conn, $query) or die (mysqli_error($conn));
        mysqli_stmt_bind_param($stmt, "sii",$replay,$read,$id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    function get_messages(){
        global $conn;
        $data=array();
        $query="SELECT * FROM contact_message ORDER BY date";
        $res=mysqli_query($conn,$query);
        while($row=mysqli_fetch_assoc($res)){
            array_push($data,array(
                "id" => $row['id'],
                "email" => $row['email'],
                "message" => $row['message'],
                "read" => $row['mread'],
                "reply" => $row['reply'],
                "date" => $row['date'],
            ));
        }
        

        return $data;
    }
?>