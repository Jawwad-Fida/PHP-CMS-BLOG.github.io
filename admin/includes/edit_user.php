<?php

if(isset($_GET['u_id'])){

    //catch value from url
    $the_user_id = $_GET['u_id'];

    //get data from database
    $sql = "SELECT * FROM users WHERE user_id={$the_user_id}";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $username = $row['username'];
        $user_firstname = $row['user_firstname'];
        $user_lastname = $row['user_lastname'];
        $user_email = $row['user_email'];
        $user_role = $row['user_role'];
    }

}
//pass value into the fields(form) from database by using value="" in <input>
?>

<?php

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
    WHERE user_id=?";   

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: users.php?error=sql");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"ssssssi",$username,$passwordHash,$user_firstname,$user_lastname,$user_email,$user_role,$the_user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: users.php?input=update");
}
?>


<h1 style="text-align:center;font-weight:bold;color:brown">EDIT USERS</h1>
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

            <?php
            //adding select options to user role

            if($user_role == 'Admin'){
                //if user role is Admin, give option to change to Subscriber
                echo "<option value='Subscriber'>Subscriber</option>"; 
            }
            else{
                //if user role is Subscriber, give option to change to Admin
                echo "<option value='Admin'>Admin</option>";
            }
            ?>

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
        <input class="btn btn-primary" type="submit" name="edit_user" value="Update User">
    </div>

</form>


