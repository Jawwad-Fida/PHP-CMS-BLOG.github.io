<?php

//====================================== Database Functions ========================================

function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function query($sql){
    global $conn;
    $result = mysqli_query($conn, $sql);
    confirmQuery($result);
    return $result;
}

function confirmQuery($result) {
    global $conn;

    if(!$result){
        die("Query Failed " .mysqli_error($conn));
    }
}



//=====================================================================================================




//=============================== General Functions =============================


//for add_post.php, edit_post.php
function upload_image($post_image,$post_image_temp,$fileError,$fileSize){
    global $conn;

    $fileArray = explode('.',$post_image); //explode() - creates an array
    //remove '.' dot extension from file. doing so, we get 2 parts: - name of file and extension

    //end() - get last data, and convet it to lowercase
    $fileExt  = strtolower(end($fileArray));

    $allowedExt = array('jpg','jpeg','png'); //file extensions allowed

    //check if file is allowed to be uploaded
    if(in_array($fileExt,$allowedExt)){
        //check for errors
        if($fileError === 0){
            //check for file size
            if($fileSize < 700000){
                //7mb = 700000 kb

                //upload the file

                //specify destination
                $fileDestination = "../images/$post_image";

                //move from temporary location to destination - upload file function
                move_uploaded_file($post_image_temp,$fileDestination);
            }
        }
    }
}


//ADMIN DETECTION FEATURE 
function is_admin(){
    global $conn;

    if(isLoggedIn()){
        //select user_role column 
        $name = $_SESSION['user_name'];
        $sql = "SELECT user_role FROM users WHERE username = '{$name}'";
        $result = mysqli_query($conn,$sql);

        $row = mysqli_fetch_assoc($result); //fetch data from table

        if($row['user_role'] == 'Admin'){
            return true;
        }
        else{
            return false;
        }

    }

}


//checks if user is logged in
function isLoggedIn(){
    //accept session for log in

    if(isset($_SESSION['role'])){
        return true;
    }

    return false;
}


function currentUser(){

    if(isset($_SESSION['user_name'])){
        return $_SESSION['user_name'];
    }

    return false;
}


function loggedInUserId(){
    if(isLoggedIn()){
        $result = query("SELECT * FROM users WHERE username='{$_SESSION['user_name']}'");
        confirmQuery($result);
        $user = mysqli_fetch_array($result);
        return mysqli_num_rows($result) >= 1 ? $user['userID'] : false;
    }
    return false;

}

function get_username(){
    return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
}

function get_user_role(){
    return isset($_SESSION['role']) ? $_SESSION['role'] : null;
}


//get the total number of likes of a post
function get_likes($the_post_id){
    global $conn;

    $result = query("SELECT * FROM posts WHERE post_id={$the_post_id}");
    confirmQuery($result);
    $row = mysqli_fetch_assoc($result);
    return $row['likes'];
}


//get the total number of dislikes of a post
function get_dislikes($the_post_id){
    global $conn;

    $result = query("SELECT * FROM posts WHERE post_id={$the_post_id}");
    confirmQuery($result);
    $row = mysqli_fetch_assoc($result);
    return $row['dislikes'];
}


/*
function username_exists($username){

    global $connection;

    $query = "SELECT username FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if(mysqli_num_rows($result) > 0) {
        return true;

    } else {
        return false;

    }
}


function email_exists($email){

    global $connection;

    $query = "SELECT user_email FROM users WHERE user_email = '$email'";
    $result = mysqli_query($connection, $query);
    confirmQuery($result);

    if(mysqli_num_rows($result) > 0) {

        return true;

    } else {

        return false;

    }

}

*/

//=========================================================================================





//=============================================== Category Functions ================================


//FOR categories.php (admin)
function insert_categories(){
    global $conn; //connection has to be global

    if (isset($_POST['submit'])) {
        $cat_title = validate($_POST['cat_title']);
        $cat_title = mysqli_real_escape_string($conn, $cat_title);

        $cat_size = strlen($cat_title);

        //Check for Errors
        if(empty($cat_title)) {
            header("Location: categories.php?error=empty");
            exit();
        }
        elseif(!preg_match("/^[a-zA-Z]*$/",$cat_title)){
            header("Location: categories.php?error=invalid");
            exit();  
        }
        elseif($cat_size <= 2){
            //check if length of category is valid
            header("Location: categories.php?error=size");
            exit();  
        }


        $sql = "INSERT INTO categories(cat_title) VALUES(?)";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: categories.php?error=sql");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "s", $cat_title);
        mysqli_stmt_execute($stmt);
        header("Location: categories.php?input=success");
        mysqli_stmt_close($stmt);
    }

}


//FOR categories.php (admin)
function findAllCategories(){
    global $conn;

    $sql = "SELECT * FROM categories";
    $result = mysqli_query($conn, $sql);

    //AFTER FINDING CATEGORIES, CREATE LINKS TO UPDATE OR DELETE THEM

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<td>{$cat_id}</td>";
        echo "<td>{$cat_title}</td>";

        //1st link - to delete categories by clicking on link - we will pass value to url 

        //2nd link - select which categories to update by clicking on link - we will pass value to url

        //-----pass id from database to url------
        echo "<td><a class='btn btn-danger' href='categories.php?delete={$cat_id}'>Delete</a></td>";
        echo "<td><a class='btn btn-success' href='categories.php?edit={$cat_id}'>Edit</a></td>";
        echo "<tr>";
    }

}


//FOR categories.php (admin)
function delete_categories(){
    global $conn;

    if (isset($_GET['delete'])) {

        //get information from url

        //since we are getting the id from url, grab it.
        $the_cat_id = $_GET['delete'];

        $sql = "DELETE FROM categories WHERE cat_id = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: categories.php?error=sql");
            exit();
        }

        mysqli_stmt_bind_param($stmt, "i", $the_cat_id);
        mysqli_stmt_execute($stmt);
        header("Location: categories.php?input=Delete");
        mysqli_stmt_close($stmt);
    }
}



//=========================================================================================





//========================================= User Functions ===================================


//FOR admin_navigation.php
function users_online(){
    //using php+jquery+ajax = to get instant results from database (to see users online without refreshing)

    if(isset($_GET['onlineusers'])){

        global $conn;

        //since we are coming here from script.js, the database connection is not included. 

        //check if the connection is available
        if(!$conn){
            //if connection is not available, include the connection in order to get a session

            session_start();
            include "../includes/connect.php";

            $session = session_id(); //this function will catch the id of any user (session) who logs in. Session ids are unique based on browsers

            $time = time(); //time() returns current time as a Unix timestamp

            $time_out_in_seconds = 300; //5 mins = 300s //how long a user can stay logged in to a website //automatically log out user if time limit exceeds 

            //calculation to find if the user has exceeded time limit
            $time_out = $time - $time_out_in_seconds;
            //if time >, then we know that they are not active

            //format the current time in time() to a date
            $date_log_in = date("Y-m-d",$time);

            $sql = "SELECT * FROM users_online WHERE session='{$session}'";
            $result = mysqli_query($conn,$sql);

            $rowNumbers = mysqli_num_rows($result); 

            if($rowNumbers == NULL){
                //if we see a new user(new session id), insert time and session when they first came to our website

                $sql2 = "INSERT INTO users_online(session,time,date_login) VALUES(?,?,?)";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql2);
                mysqli_stmt_bind_param($stmt,"sss",$session,$time,$date_log_in);
                mysqli_stmt_execute($stmt);
            }
            else{
                //if it is an old user (not new), it means the user has been on the website before
                //so just keep on updating time [spent on our website]

                $sql2 = "UPDATE users_online 
                SET time=?, date_login=? 
                WHERE session=?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt,$sql2);
                mysqli_stmt_bind_param($stmt,"sss",$time,$date_log_in,$session);
                mysqli_stmt_execute($stmt);      

            }

            //count the number of users online 
            $online_sql = "SELECT * FROM users_online WHERE time > '{$time_out}'";
            $online = mysqli_query($conn,$online_sql);
            $users_online = mysqli_num_rows($online);

            echo $users_online;

            //Summary: - we are keeping track of the users that we have, and at the same time, we are tracking the time they are spending on our website
            //and this is updated every time

        }

    }

}

users_online(); //now, call the function

/*
//using php only (refresh to see users online)

function users_online(){

    global $conn;

    $session = session_id(); //this function will catch the id of any user (session) who logs in. Session ids are unique based on browsers

    $time = time(); //time() returns time in seconds

    $time_out_in_seconds = 300; //5 mins = 300s //how long the user can stay logged in to the website //automatically log out user if time limit exceeds 

    //calculation
    $time_out = $time - $time_out_in_seconds;

    $sql = "SELECT * FROM users_online WHERE session='{$session}'";
    $result = mysqli_query($conn,$sql);

    $rowNumbers = mysqli_num_rows($result);

    if($rowNumbers == NULL){
        //if we see a new user(new session id), insert time and session when they first came to our website

        $sql2 = "INSERT INTO users_online(session,time) VALUES(?,?)";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt,$sql2);
        mysqli_stmt_bind_param($stmt,"ss",$session,$time);
        mysqli_stmt_execute($stmt);
    }
    else{
        //if it is an old user (not new), it means the user has been on the website before
        $sql2 = "UPDATE users_online SET time=? WHERE session=?";
        $stmt = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmt,$sql2);
        mysqli_stmt_bind_param($stmt,"ss",$time,$session);
        mysqli_stmt_execute($stmt);      

    }

    //count the number of users online 
    $online_sql = "SELECT * FROM users_online WHERE time > '{$time_out}'";
    $online = mysqli_query($conn,$online_sql);
    $users_online = mysqli_num_rows($online);

    return $users_online;

    //we are keeping track of the users that we have, and at the same time, we are tracking the time they are spending on our website
    //and this is updated every time

}

*/

//FOR dashboard.php (admin)
function recordCount($table){

    global $conn;

    $sql = "SELECT * FROM $table";
    $result = mysqli_query($conn,$sql);

    $count_rows = mysqli_num_rows($result);
    return $count_rows;

}


//FOR dashboard.php
function checkUserRole($table,$column,$status){

    global $conn;

    $query = "SELECT * FROM $table WHERE $column = '$status'";
    $result = mysqli_query($conn,$query);

    $count = mysqli_num_rows($result); 
    return $count;

}




function imagePlaceholder($image=''){

    if(!$image){
        //then use the default placeholder image
        return 'image_4.jpg';
    }
    else{
        //if image is found, return the image itself
        return $image;
    }

}

//<?php echo imagePlaceholder($post_image); 


function get_all_user_posts(){
    return query("SELECT * FROM posts WHERE post_author='{$_SESSION['user_name']}'");
}


function count_records($result){
    return mysqli_num_rows($result);
}


function get_all_user_published_posts(){
    return query("SELECT * FROM posts WHERE post_author='{$_SESSION['user_name']}' AND post_status='published'");
}


function get_all_user_draft_posts(){
    return query("SELECT * FROM posts WHERE post_author='{$_SESSION['user_name']}' AND post_status='draft'");
}


//These are posts and comments - 2 tables, we will JOIN the tables
//we will do INNER JOIN 

function get_all_posts_user_comments(){
    return query("SELECT * FROM posts
    INNER JOIN comments ON posts.post_title = comments.comment_post_title
    WHERE post_author='{$_SESSION['user_name']}'");
}

function get_all_user_pending_posts_comments(){
    return query("SELECT * FROM posts
    INNER JOIN comments ON posts.post_title = comments.comment_post_title
    WHERE post_author='{$_SESSION['user_name']}' AND comment_status='Pending'");
}

function get_all_user_approved_posts_comments(){
    return query("SELECT * FROM posts
    INNER JOIN comments ON posts.post_title = comments.comment_post_title
    WHERE post_author='{$_SESSION['user_name']}' AND comment_status='Approved'");
}


function get_all_user_unapproved_posts_comments(){
    return query("SELECT * FROM posts
    INNER JOIN comments ON posts.post_title = comments.comment_post_title
    WHERE post_author='{$_SESSION['user_name']}' AND comment_status='Unapproved'");
}


//================================================================================







