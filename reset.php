<?php  
include "includes/connect.php"; 
include "includes/header.php"; 

function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>

<?php

if(!isset($_GET['email']) && !isset($_GET['token'])){
    header("Location: index.php");
}
else{
    
    if(isset($_POST['reset_submit'])){

        //we have to make sure that the information we are receiving from the email belongs to the right user

        $email = $_GET['email'];
        $token = $_GET['token'];

        $sql = "SELECT username,user_email,token FROM users WHERE token='{$token}'";

        $result = mysqli_query($conn,$sql);

        if(!$result){
            header("Location: reset.php?error=sql");
            exit();
        }

        $row = mysqli_fetch_assoc($result);

        //check if the tokens and email match the correct user in database
        if($_GET['token'] !== $row['token'] || $_GET['email'] !== $row['email'] ){
            header("Location: index.php");
        }

        $password = validate($_POST['password']);
        $password = mysqli_real_escape_string($conn,$password);

        $password_repeat = validate($_POST['password_repeat']);
        $password_repeat = mysqli_real_escape_string($conn,$password_repeat);

        $password_size = strlen($password); //get size of password

        //------------------------------CHECKING FOR ERRORS------------------

        if(empty($password) || empty($password_repeat)){
            header("Location: reset.php?error=emptyfields"); 
            exit(); //stop script from running
        }
        elseif($password_size <= 4){
            //check if length of password is valid
            header("Location: reset.php?error=invalid_pwd_length"); 
            exit();  
        }
        elseif($password !== $password_repeat){
            //check if password are same 
            header("Location: reset.php?error=pwd_no_match"); 
            exit();  
        }

        //---------------------------------------

        //updating password and token columns

        $passwordHash = password_hash($password,PASSWORD_DEFAULT);

        //once we are done using the token, we don't want it anymore
        $token_update = "Token Used";
        $sql = "UPDATE users SET token=?,user_password=? WHERE user_email=?";

        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt,$sql)){
            header("Location: reset.php?error=sql");
            exit();
        }

        mysqli_stmt_bind_param($stmt,"sss",$token_update,$passwordHash,$email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        header("Location: login_form_recover.php?success=change");
    }
}
?>


<!-- Navigation -->
<?php  include "includes/navigation.php"; ?>

<div class="container">

    <?php
    //error messages
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'emptyfields') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Please Fill in the Fields!<p>";
        }
        elseif ($_GET['error'] == 'sql') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Error in SQL!<p>";
        }
        elseif ($_GET['error'] == 'invalid_pwd_length') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Password has to be more than 4 characters!<p>";
        }
        elseif ($_GET['error'] == 'pwd_no_match') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Passwords does not match!<p>";
        }
    }

    //success message
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'sent') {
            echo "<p style='color:green;font-size:25px;text-align:center'>Email Sent! Please check your inbox<p>";
        }
    }

    ?>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Reset Password</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">

                                <form action="" id="register-form" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input id="password" name="password" placeholder="Enter new password" class="form-control"  type="password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-ok color-blue"></i></span>
                                            <input id="confirmPassword" name="password_repeat" placeholder="Repeat new password" class="form-control"  type="password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="reset_submit" class="form-control btn btn-primary">Reset Password</button>
                                    </div>

                                    <input type="hidden" class="hide" name="token" id="token" value="">
                                </form>

                            </div><!-- Body-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->
