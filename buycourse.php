<?php
function buy($cid){
    global $error;
    $course=view_course($cid,1);
    if(!isset($course['cid'])){
        echo "Course id is not valid!";
        exit();
    }
    $user=view_profile($_SESSION['uid'],null);
    $owner=view_profile($course['autorid'], null);
    $admin=view_profile(1, null);
    if(doubleval($course['price'])<=doubleval($user['balance'])){
        $u_b=doubleval($user['balance'])-doubleval($course['price']);
        $o_b=doubleval($owner['balance'])+0.75*doubleval($course['price']);
        $a_b=doubleval($admin['balance'])+0.25*doubleval($course['price']);
        update_balance($user['id'],$u_b);
        update_balance($owner['id'],$o_b);
        update_balance($admin['id'],$a_b);
        buy_course($user['id'], $cid);
        delete_cart($user['id'], $cid);
        
    }
    else{
        $error="ERROR: You Don't have enough money!";
        $err="error";
        $expiree= time() + (1);
        setcookie($err, $error, $expiree);
    }
}
?>