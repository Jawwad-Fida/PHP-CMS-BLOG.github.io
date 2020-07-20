<?php  
include "includes/connect.php"; 
include "includes/header.php";
?>

<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'Classes/config.php';
?>

<?php

function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function email_exists($email){
    global $conn;

    $sql = "SELECT * FROM users WHERE user_email='$email';"; 
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result); 

    if($resultCheck <= 0){
        //email does not exist
        return true;    
    }
    else{
        return false;
    }
}


if(isset($_POST['forgot_submit'])){

    $email = validate($_POST['email']);
    $email = mysqli_real_escape_string($conn,$email);

    //check if inputs are empty
    if(empty($email)){
        header("Location: forgot.php?error=emptyfields");
        exit();
    }

    //check if email exists
    if(email_exists($email) == 'true'){
        header("Location: forgot.php?error=email_does_not_exist"); 
        exit(); 
    }


    //Creating Tokens
    $length = 50;
    $token = bin2hex(openssl_random_pseudo_bytes($length));

    //Updating database with token values
    $sql = "UPDATE users SET token=? WHERE user_email=?";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: forgot.php?error=sql");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ss",$token,$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    //---------CONFIGURE PHP MAILER ----------------

    $mail = new PHPMailer(true);

    //check if class exists
    //echo get_class($mail);

    try {
        //access class
        
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                   
        $mail->isSMTP();                                         
        $mail->Host = Config::SMTP_HOST;
        $mail->Username = Config::SMTP_USER;                   
        $mail->Password = Config::SMTP_PASSWORD;
        $mail->Port = Config::SMTP_PORT; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->SMTPAuth = true;

        //Recipients
        $mail->setFrom('jawwadFida@example.com', 'Jawwad Fida');
        $mail->addAddress($email);    

        // Content
        $mail->isHTML(true);                                  
        $mail->Subject = 'Password Reset Email';
        $mail->Body = "<p>Please click here to reset your password: - 
        <a href='http://localhost/phpDemo/PHP%20Blogging%20System/reset.php?email={$email}&token={$token}' target='_blank'>http://localhost/phpDemo/PHP%20Blogging%20System/reset.php?email={$email}&token={$token}</a>
        </p>";
        
        $mail->send();
        header("Location: forgot.php?success=sent");
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}
?>

<!-- Page Content -->
<div class="container">

    <?php
    //error messages
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'emptyfields') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Please Fill in the Field!<p>";
        }
        elseif ($_GET['error'] == 'sql') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Error in SQL!<p>";
        }
        elseif ($_GET['error'] == 'email_does_not_exist') {
            echo "<p style='color:red;font-size:25px;text-align:center'>Email does not exist!<p>";
        }
    }
    
    //success message
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'sent') {
            echo "<p style='color:green;font-size:25px;text-align:center'>Email Sent! Please check your inbox<p>";
        }
    }
    
    ?>

    <div class="form-gap"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">

                                <form id="register-form" action="" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="Enter your E-mail Address" class="form-control" type="email">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="forgot_submit" class="form-control btn btn-primary">Send Reset Email</button>
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

