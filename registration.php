<?php  
include "includes/connect.php";
include "includes/header.php";

//---------------------------------------------------------

include "vendor/autoload.php";

//make sure the data in .env file is globally declared

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

//create a new instance of pusher - namespace
//$pusher = new Pusher\Pusher('key','secret','app-key','options');

$options = array(
    'cluster' => 'ap2',
    'useTLS' => true
);

//pull the information from .env file
$pusher = new Pusher\Pusher(getenv('APP_KEY'), getenv('APP_SECRET'), getenv('APP_ID'), $options);

//------------------------------------------------------


function validate($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

function username_exists($username){
    global $conn;

    $sql = "SELECT * FROM users WHERE username='$username';"; 
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result); //fetch number of rows(data)  

    if($resultCheck > 0){
        return true;    
    }
    else{
        return false;
    }
}

function email_exists($email){
    global $conn;

    $sql = "SELECT * FROM users WHERE user_email='$email';"; 
    $result = mysqli_query($conn,$sql);
    $resultCheck = mysqli_num_rows($result); 

    if($resultCheck > 0){
        return true;    
    }
    else{
        return false;
    }
}


//------------------ SETTING LANGUAGE VARIABLES ----------------------


if(isset($_GET['lang']) && !empty($_GET['lang'])){

    //Assign the data sent by form to a session so that we dont lose value when page is refreshed
    $_SESSION['lang'] = $_GET['lang'];

    //reload the page depending on the data
    if(isset($_SESSION['lang']) && $_SESSION['lang'] != $_GET['lang']){
        //if the language is still english, we will refresh the page so that our data will pop up
        echo "<script type='text/javascript'> location.reload(); </script>";  
    }

}

//get the language files 
if(isset($_GET['lang'])){
    //different language files in languages directory
    include "includes/languages/" .$_SESSION['lang'] .".php";
}
else{
    //english is default
    include "includes/languages/en.php";
}

//-------------------------------------------------------------


if(isset($_POST['submit'])){

    $username = validate($_POST['username']);
    $username = mysqli_real_escape_string($conn,$username);

    $email = validate($_POST['email']);
    $email = mysqli_real_escape_string($conn,$email);

    $password = validate($_POST['password']);
    $password = mysqli_real_escape_string($conn,$password);

    $password_repeat = validate($_POST['password_repeat']);
    $password_repeat = mysqli_real_escape_string($conn,$password_repeat);


    $password_size = strlen($password); //get size of password
    $username_size = strlen($username); //get size of username


    //------------------------------CHECKING FOR ERRORS------------------

    if(empty($username) || empty($email) || empty($password) || empty($password_repeat)){
        header("Location: registration.php?error=emptyfields"); 
        exit(); //stop script from running
    }
    elseif(!preg_match("/^[a-zA-Z]*$/",$username)){
        //check if input characters are valid
        header("Location: registration.php?error=valid_name"); 
        exit();  
    }
    elseif($username_size <= 2){
        //check if length of username is valid
        header("Location: registration.php?error=invalid_name_length"); 
        exit();  
    }
    elseif($password_size <= 4){
        //check if length of password is valid
        header("Location: registration.php?error=invalid_pwd_length"); 
        exit();  
    }
    elseif($password !== $password_repeat){
        //check if password are same
        header("Location: registration.php?error=pwd_no_match"); 
        exit();  
    }

    //--------------------------------CHECKING FOR DUPLICATE USERS AND EMAILS (by calling functions above)-------------

    if(username_exists($username) == 'true'){
        header("Location: registration.php?error=user_exists"); 
        exit();
    }

    if(email_exists($email) == 'true'){
        header("Location: registration.php?error=email_exists"); 
        exit(); 
    }

    //-------------------------QUERY------------------------

    $user_role = 'Subscriber';
    $passwordHash = password_hash($password,PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(username,user_password,user_email,user_role) VALUES(?,?,?,?)";   

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: registration.php?error=sql");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ssss",$username,$passwordHash,$email,$user_role);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    //------------NOTIFICATION----------------

    $data['message'] = $username;

    $pusher->trigger('notifications','new_user',$data);

    header("Location: registration.php?input=success");

}
?>


<!-- Navigation -->

<?php  include "includes/navigation.php"; ?>

<!-- Page Content -->
<div class="container">
    

    <!--------------------- Form to change language ------------------->
    <form method="get" class="navbar-form navbar-right" action="" id="language_form">
        <div class="form-group">

            <!--  onChange() event handler will execute changeLanguage() function -->
            <!--  data in value="" will be sent as get -->
            <select name="lang" class="form-control" onchange="changeLanguage()">
                <option value="en" 
                        <?php
                        if(isset($_SESSION['lang']) && $_SESSION['lang']=='en'){
                            echo "selected";
                        }
                        ?>>English</option>
                <option value="bangla" 
                        <?php 
                        if(isset($_SESSION['lang']) && $_SESSION['lang']=='bangla'){
                            echo "selected";
                        }
                        ?>>Bangla</option>
            </select>

        </div>
    </form>
    <!----------------------------------------------------------------->

    
    <section id="login">
        <div class="container">

            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">

                    <div class="form-wrap">

                        <?php
                        //error messages
                        if (isset($_GET['error'])) {
                            if ($_GET['error'] == 'sql') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Error connecting to database!<p>";
                            }
                            elseif ($_GET['error'] == 'emptyfields') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Fill in all the Fields!<p>";
                            }
                            elseif ($_GET['error'] == 'pwd_no_match') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Passwords do not match!<p>";
                            }
                            elseif ($_GET['error'] == 'valid_name') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>No special characters allowed for username!<p>";
                            }
                            elseif ($_GET['error'] == 'invalid_name_length') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Username has to be more than 2 Characters !<p>";
                            }
                            elseif ($_GET['error'] == 'invalid_pwd_length') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Password has to be more than 4 Characters!<p>";
                            }
                            elseif ($_GET['error'] == 'user_exists') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>Username already exists!<p>";
                            }
                            elseif ($_GET['error'] == 'email_exists') {
                                echo "<p style='color:red;font-size:25px;text-align:center'>E-mail already exists!<p>";
                            }
                        }
                        ?>

                        <?php
                        //success messages
                        if (isset($_GET['input'])) {
                            if ($_GET['input'] == 'success') {
                                echo "<p style='color:green;font-size:25px;text-align:center'>Registration Successfull!<p>";

                            }
                        }
                        ?>

                        
                        <!--------------- We are using language files (here) ---------------------->
                        <h1 style='text-align:center'><?php echo _REGISTER; ?></h1>

                        <form role="form" action="" method="post" id="login-form" autocomplete="off">

                            <div class="form-group">
                                <label for="username" class="sr-only">Username</label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo _USERNAME; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="<?php echo _EMAIL; ?>">
                            </div>

                            <div class="form-group">
                                <label for="password" class="sr-only">Password</label>
                                <input type="password" name="password" id="key" class="form-control" placeholder="<?php echo _PASSWORD; ?>">
                            </div>

                            <div class="form-group">
                                <label for="password" class="sr-only">Repeat Password</label>
                                <input type="password" name="password_repeat" id="key" class="form-control" placeholder="<?php echo _PASSWORD_AGAIN; ?>">
                            </div>

                            <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="<?php echo _REGISTER; ?>">

                        </form>

                    </div>

                </div> <!-- /.col-xs-12 -->
            </div> <!-- /.row -->

        </div> <!-- /.container -->
    </section>

    <hr>

   <!------------- JQuery code for trigger the action of switching languages ------------>
    <script>
        
        function changeLanguage(){
            document.getElementById('language_form').submit();
        }

    </script>

    <?php include "includes/footer.php";?>
