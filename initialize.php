<?php
    $user='root';
    $pass='';
    $ip='localhost';
    $conn=mysqli_connect($ip,$user,$pass);
    if(!$conn){
        die("ERROR: mysql connection error".mysqli_connect_error($conn));
    }
    $db_query='CREATE DATABASE IF NOT EXISTS Elearn';
    $admin_pass=md5('admin');
    $table_query="CREATE TABLE IF NOT EXISTS users(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    username VARCHAR(30) NOT NULL UNIQUE, email VARCHAR(70) NOT NULL, bio TEXT DEFAULT '', password VARCHAR(50) NOT NULL, active BOOLEAN DEFAULT FALSE, staff BOOLEAN DEFAULT FALSE, admin BOOLEAN DEFAULT FALSE)";
    $root_add="INSERT INTO users(username,email,password,active,staff,admin) VALUES ('admin','admin@elearn.com','$admin_pass',true,true,true)";
    
    if(mysqli_query($conn,$db_query)){
        echo "database Elearn created sucessfully";
        mysqli_select_db($conn, 'Elearn');
        if(mysqli_query($conn,$table_query)){
            echo "table created";
            if(mysqli_query($conn, $root_add)){
                echo "root created";
            }
            else{
                echo "ERROR: root not created".mysqli_error($conn);
            }
        }
        else{
            echo "Error: occured";
        }
    }
    else {
        echo "ERROR: Can't create database". mysqli_error($conn);
    }
 
    function createTable($conn, $query){
        if(!mysqli_query($conn, $query)){
            die("ERROR: ".mysqli_error($conn));
        }        
    }
    $course_tq="CREATE TABLE IF NOT EXISTS courses(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, title VARCHAR(60) NOT NULL, description TEXT NOT NULL,
    category VARCHAR(30) NOT NULL, price DOUBLE NOT NULL DEFAULT 0.0, thumbnail TEXT NOT NULL, view INT NOT NULL DEFAULT 0, uploadDate DATE NOT NULL DEFAULT CURRENT_TIMESTAMP, private BOOLEAN NOT NULL DEFAULT FALSE, active BOOLEAN NOT NULL DEFAULT FALSE, 
    autorid INT NOT NULL, FOREIGN KEY (autorid) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE);";
    $course_vid_tq="CREATE TABLE IF NOT EXISTS videos(id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, title VARCHAR(60) NOT NULL, description TEXT NOT NULL DEFAULT '', video VARCHAR(120) NOT NULL, courseId INT NOT NULL, FOREIGN KEY (courseId) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE)";
    $enrolled_course="CREATE TABLE IF NOT EXISTS enrolled_courses(
        uid INT NOT NULL,
        cid INT NOT NULL,
        PRIMARY KEY(uid,cid),
        CONSTRAINT FOREIGN KEY(uid) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
        CONSTRAINT FOREIGN KEY(cid) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE
    );";
    $billing="CREATE TABLE IF NOT EXISTS billing(
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
        full_name VARCHAR(256) NOT NULL,
        cvv VARCHAR(3) NOT NULL,
        expDate VARCHAR(5) NOT NULL,
        cardNum VARCHAR(20) NOT NULL,
        balance DOUBLE DEFAULT 0.0,
        FOREIGN KEY(id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
    );";
    $cart="CREATE TABLE IF NOT EXISTS cart(
        uid INT NOT NULL,
        cid INT NOT NULL,
        PRIMARY KEY(uid,cid),
        FOREIGN KEY(uid) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY(cid) REFERENCES courses(id) ON DELETE CASCADE ON UPDATE CASCADE
    );";
    $contact_msg="CREATE TABLE IF NOT EXISTS contact_message( 
        id INT NOT NULL PRIMARY KEY AUTO_INCREMENT , 
        email VARCHAR(60) NOT NULL , 
        message TEXT NOT NULL , 
        mread BOOLEAN NOT NULL DEFAULT FALSE , 
        reply TEXT NOT NULL DEFAULT '' , 
        date DATE NOT NULL ); 
        ";
    createTable($conn,$course_tq);
    createTable($conn, $course_vid_tq);
    createTable($conn,$billing);
    createTable($conn,$cart);
    createTable($conn,$enrolled_course);
    createTable($conn, $contact_msg);
    mysqli_close($conn);
?>