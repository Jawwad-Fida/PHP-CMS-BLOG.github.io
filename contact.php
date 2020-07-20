<?php  
include "includes/connect.php";
include "includes/header.php"; 

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

if(isset($_POST['submit'])){

    $email_to = "skyabyss13@gmail.com";//our email address

    //format the headers - email_from
    $email_from = validate($_POST['email']);
    //$email_from = "From: " .mysqli_real_escape_string($conn,$email_from);
    $email_from = mysqli_real_escape_string($conn,$email_from);


    $subject = validate($_POST['subject']);
    $subject = mysqli_real_escape_string($conn,$subject);

    $message_body = validate($_POST['body']);
    $message_body = mysqli_real_escape_string($conn,$message_body);

    //Error messages
    if(empty($email_from) || empty($subject) || empty($message_body)){
        header("Location: contact.php?error=emptyfields");
        exit();
    }

    // use wordwrap() if lines are longer than 70 characters
    $subject = wordwrap($subject,70);

    // send email by mail()
    //mail($email_to,$subject,$message_body,$email_from);


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
        $mail->setFrom($email_from, 'Admin');
        $mail->addAddress($email_to);    

        // Content
        $mail->isHTML(true);                                  
        $mail->Subject = $subject;
        $mail->Body = $message_body;

        $mail->send();
        
        header("Location: contact.php?input=success");

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

}

?>



<!-- Navigation -->

<?php  include "includes/navigation.php"; ?>


<!-- Page Content -->
<div class="container">

    <section id="login">
        <div class="container">

            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">

                    <div class="form-wrap">

                        <?php
                        //error messages
                        if (isset($_GET['error'])) {      
                            if ($_GET['error'] == 'emptyfields') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Fill in all the Fields!<p>";
                            } 
                        }
                        ?>

                        <?php
                        //success messages
                        if (isset($_GET['input'])) {
                            if ($_GET['input'] == 'success') {
                                echo "<p style='color:green;font-size:25px;text-align:center'>Mail Sent! Please wait for a reply<p>";
                            }
                        }
                        ?>


                        <h1 style='text-align:center'>Contact Page</h1>

                        <form role="form" action="" method="post" autocomplete="off"> <!-- or mail.php in includes folder -->

                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your E-mail">
                            </div>

                            <div class="form-group">
                                <label for="email" class="sr-only">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="Enter your Subject">
                            </div>

                            <div class="form-group">
                                <label for="email" class="sr-only">Message</label>
                                <textarea class="form_control" name="body" cols="74" rows="10" placeholder="Enter your message"></textarea>
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Submit">

                        </form>

                    </div>

                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->

        </div> <!-- /.container -->
    </section>

    <hr>

    <?php include "includes/footer.php";?>
