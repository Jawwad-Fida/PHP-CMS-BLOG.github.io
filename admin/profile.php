<?php
//we will make this page dynamic - i.e. it will change according to data

include "../includes/connect.php";
include "includes/admin_header.php";
include "functions.php";
?>

<div id="wrapper">

    <?php
    include "includes/admin_navigation.php";
    ?>

    <?php
    //error messages
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'sql') {
            echo "<p style='color:red;font-size:25px'>Error connecting to database!<p>";
        } 
    }
    ?>

    <?php
    //success messages
    if (isset($_GET['input'])) {
        if ($_GET['input'] == 'success') {
            echo "<p style='color:green;font-size:25px'>User Added!<p>";
        } elseif ($_GET['input'] == 'Delete') {
            echo "<p style='color:green;font-size:25px'>User Deleted!<p>";
        } elseif ($_GET['input'] == 'admin') {
            echo "<p style='color:green;font-size:25px'>Admin Priviliges Granted!<p>";
        }
        elseif ($_GET['input'] == 'subscriber') {
            echo "<p style='color:green;font-size:25px'>Role changed to Subscriber!<p>";
        }
        elseif ($_GET['input'] == 'update') {
            echo "<p style='color:green;font-size:25px'>User Information Updated!<p>";
        }
    }
    ?>

    <div id="page-wrapper">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header">
                        POSTS
                        <small>CRUD operations</small>
                    </h1>


                    <?php

                    //check if session is available
                    if(isset($_SESSION['user_name'])){
                        $session_username = $_SESSION['user_name'];  

                        $sql = "SELECT * FROM users WHERE username='{$session_username}'";
                        $result = mysqli_query($conn,$sql);

                        while($row=mysqli_fetch_assoc($result)){

                            $user_id = $row['user_id'];
                            $username = $row['username'];
                            $user_firstname = $row['user_firstname'];
                            $user_lastname = $row['user_lastname'];
                            $user_email = $row['user_email'];
                            $user_role = $row['user_role'];

                    ?>

                    <h1 style="text-align:center;font-weight:bold;color:brown">EDIT USER</h1>
                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label for="title">Firstname</label>
                            <input type="text" value="<?php echo $user_firstname; ?>" class="form-control" name="user_firstname">
                        </div>

                        <div class="form-group">
                            <label for="title">Lastname</label>
                            <input type="text" value="<?php echo $user_lastname; ?>" class="form-control" name="user_lastname">
                        </div>

                        <div class="form-group">  
                            <label for="status">User Role</label>
                            <select name="user_role" id="">
                                <!-- default option -->
                                <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
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
                            <input type="text" value="<?php echo $username; ?>" class="form-control" name="username">
                        </div>

                        <div class="form-group">
                            <label for="post_tags">Email</label>
                            <input type="text" value="<?php echo $user_email; ?>" class="form-control" name="user_email">
                        </div>

                        <div class="form-group">
                            <label for="post_tags">Password</label>
                            <input type="password" class="form-control" name="user_password">
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                        </div>

                    </form>

                    <?php
                        }
                    }
                    ?>

                </div>


            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

    <?php
    //form validation for update user

    if(isset($_POST['edit_user'])){

        $user_firstname = validate($_POST['user_firstname']);
        $user_firstname = mysqli_real_escape_string($conn,$user_firstname);

        $user_lastname = validate($_POST['user_lastname']);
        $user_lastname = mysqli_real_escape_string($conn,$user_lastname);

        $user_role = validate($_POST['user_role']);
        $user_role = mysqli_real_escape_string($conn,$user_role);

        $username = validate($_POST['username']);
        $username = mysqli_real_escape_string($conn,$username);

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

        $passwordHash = password_hash($user_password,PASSWORD_DEFAULT);

        //UPLOAD FILE
        //upload_image($post_image,$post_image_temp,$fileError,$fileSize); //function call 

        //QUERY

        $sql = "UPDATE users
    SET username=?, user_password=?, user_firstname=?, user_lastname=?, user_email=?, user_role=?
    WHERE username=?";   

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header("Location: users.php?error=sql");
            exit();
        }

        mysqli_stmt_bind_param($stmt,"sssssss",$username,$passwordHash,$user_firstname,$user_lastname,$user_email,$user_role,$session_username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: users.php?input=update");
    }
    ?>


    <?php
    include "includes/admin_footer.php";
    ?>

