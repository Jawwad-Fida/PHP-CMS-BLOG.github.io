<?php
//error messages
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'sql') {
        echo "<p style='color:red;font-size:25px'>Error connecting to database!<p>";
    }
    elseif ($_GET['error'] == 'emptyfields') {
        echo "<p style='color:red;font-size:25px'>Please Fill in all the Fields!<p>";
    }
    elseif ($_GET['error'] == 'invalidmail') {
        echo "<p style='color:red;font-size:25px'>Please use a correct format for email!<p>";
    }
    elseif ($_GET['error'] == 'invaliduid') {
        echo "<p style='color:red;font-size:25px'>No special characters allowed for username!<p>";
    }
    elseif ($_GET['error'] == 'invaliduidsize') {
        echo "<p style='color:red;font-size:25px'>Username has to be more than 3 Characters !<p>";
    }
    elseif ($_GET['error'] == 'invalidpwdsize') {
        echo "<p style='color:red;font-size:25px'>Password has to be more than 5 Characters!<p>";
    }
}
?>



<?php

if(isset($_POST['create_user'])){

    $user_firstname = validate($_POST['user_firstname']);
    $user_firstname = mysqli_real_escape_string($conn,$user_firstname);

    $user_lastname = validate($_POST['user_lastname']);
    $user_lastname = mysqli_real_escape_string($conn,$user_lastname);

    $user_role = validate($_POST['user_role']);
    $user_role = mysqli_real_escape_string($conn,$user_role);

    $username = validate($_POST['username']);
    $username = mysqli_real_escape_string($conn,$username);

    $user_size = strlen($username); //get size of username

    /*
    //FILES have 5 properties, we are taking 2
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $fileError = $_FILES['image']['error'];
    $fileSize = $_FILES['image']['size'];
    */

    $user_email = validate($_POST['user_email']);
    $user_email = mysqli_real_escape_string($conn,$user_email);

    $user_password = validate($_POST['user_password']);
    $user_password = mysqli_real_escape_string($conn,$user_password);

    $password_size = strlen($user_password); //get size of password

    //UPLOAD FILE
    //upload_image($post_image,$post_image_temp,$fileError,$fileSize); //function call 

    //CHECKING FOR ERRORS
    if(empty($username) || empty($user_email) || empty($user_password) || empty($user_firstname) || empty($user_lastname) || empty($user_role)){
        header("Location: users.php?source=add_user&error=emptyfields&uid=".$username."&mail=".$user_email); 
        exit(); //stop script from running
    }
    elseif(!filter_var($user_email,FILTER_VALIDATE_EMAIL)){
        //check if email is valid
        header("Location: users.php?source=add_user&error=invalidmail&uid=".$username); //sending back username (correct)
        exit();  
    }
    elseif(!preg_match("/^[a-zA-Z]*$/",$username)){
        //check if input characters are valid
        header("Location: users.php?source=add_user&error=invaliduid&mail=".$user_email); 
        exit();  
    }
    elseif($user_size <= 2){
        //check if length of username is valid
        header("Location: users.php?source=add_user&error=invaliduidsize&mail=".$email);
        exit();  
    }
    elseif($password_size <= 4){
        //check if length of password is valid
        header("Location: users.php?source=add_user&error=invalidpwdsize&uid=".$username."&mail=".$user_email); 
        exit();  
    }

    $passwordHash = password_hash($user_password,PASSWORD_DEFAULT);

    //QUERY

    $sql = "INSERT INTO users(username,user_password,user_firstname,user_lastname,user_email,user_role) VALUES(?,?,?,?,?,?)";   

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: users.php?error=sql");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ssssss",$username,$passwordHash,$user_firstname,$user_lastname,$user_email,$user_role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: users.php?input=success");
}
?>


<!-- Creating Post HTML form in admin -->

<!-- action="" means same page, # means default[no change] -->
<h1 style="text-align:center;font-weight:bold;color:brown">ADD USERS</h1>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Firstname</label>
        <input type="text" class="form-control" name="user_firstname">
    </div>

    <div class="form-group">
        <label for="title">Lastname</label>
        <input type="text" class="form-control" name="user_lastname">
    </div>

    <div class="form-group">  
        <label for="status">User Role</label>
        <select name="user_role" id="">
            <option value="">Select Options</option>
            <option value="Admin">Admin</option>
            <option value="Subscriber">Subscriber</option>
        </select>
    </div>

    <!-- INPUT FIELD TO UPLOAD FILES (IMAGES) 
<div class="form-group">
<label for="post_image">Post Image</label>
<input type="file"  name="image">
</div>
-->

    <div class="form-group">
        <label for="post_tags">Username</label>
        <input type="text" class="form-control" name="username">
    </div>

    <div class="form-group">
        <label for="post_tags">Email</label>
        <input type="text" class="form-control" name="user_email">
    </div>

    <div class="form-group">
        <label for="post_tags">Password</label>
        <input type="password" class="form-control" name="user_password">
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Create User">
    </div>

</form>



