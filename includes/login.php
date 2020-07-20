<?php
ob_start();
include "connect.php";

function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

if(isset($_POST['login_submit'])){

    $username = validate($_POST['username']);
    $username = mysqli_real_escape_string($conn,$username);

    $password = validate($_POST['password']);
    $password = mysqli_real_escape_string($conn,$password);

    //check if inputs are empty
    if(empty($username) || empty($password)){
        header("Location: ../index.php?error=emptyfields");
        exit();
    }

    //check if username exists for login
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn,$sql);
    $rowNumber = mysqli_num_rows($result);

    if($rowNumber <= 0){
        header("Location: ../index.php?error=username");
        exit();
    }

    //since username exists for login, get password from database to see if it matches the password entered by the user to login
    if($row=mysqli_fetch_assoc($result)){

        //grab the password from the user that exists

        //dehash the password from database (returns true or false). Password match - true, password doesnt match - false
        $pwdCheck = password_verify($password,$row['user_password']);

        //if password doesn't match
        if($pwdCheck == false){
            //dont log in user
            header("Location: ../index.php?error=password");
            exit();
        }
        else{
            //password is correct(true)

            session_start(); //start a session

            //store information of user from database into global session variable, $_SESSION['variable_name'] = data
            $_SESSION['userID'] = $row['user_id'];
            $_SESSION['user_name'] = $row['username'];
            $_SESSION['role'] = $row['user_role'];
            $_SESSION['first_name'] = $row['user_firstname'];
            $_SESSION['last_name'] = $row['user_lastname'];

            //session_start() should be on all pages in order to check for global session variable 
            //sessions are stored in a server

            header("Location: ../admin/index.php?success=login");
        }
    }       
}
else{
    header("Location: ../index.php");
}

ob_end_flush();
mysqli_close($conn);