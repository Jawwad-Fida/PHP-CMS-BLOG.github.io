<!-- Build a TABLE to display all posts-->
<table class='table table-bordered table-hover'>

    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>  
            <th>Role</th>      
            <th>Change Role</th>      
            <th>Change Role</th>      
            <th>Edit User</th>      
            <th>Delete User</th>      
        </tr>
    </thead>

    <tbody>       

        <?php
        //recent comments are shown first - ordered in descending order
        
        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];
            $username = $row['username'];
            $user_firstname = $row['user_firstname'];
            $user_lastname = $row['user_lastname'];
            $user_email = $row['user_email'];
            $user_role = $row['user_role'];

            //close off php
        ?>

        <tr>
            <td><?php echo $user_id; ?></td>
            <td><?php echo $username; ?></td>
            <td><?php echo $user_firstname; ?></td> 
            <td><?php echo $user_lastname; ?></td>   
            <td><?php echo $user_email; ?></td>
            <td><?php echo $user_role; ?></td>
            
            <!-- PASS parameters to url -->
            
            <td><a class="btn btn-primary" href="users.php?change_to_admin=<?php echo $user_id;?>">Admin</a></td>
            
            <td><a class="btn btn-primary" href="users.php?change_to_subscriber=<?php echo $user_id;?>">Subscriber</a></td>
            
            <td><a class="btn btn-success" href="users.php?source=edit_user&u_id=<?php echo $user_id;?>">Edit</a></td>
  
            <td><a class="btn btn-danger" href="users.php?delete=<?php echo $user_id;?>">Delete</a></td>
            
        </tr>

        <?php
            //open up php, and close loop
        }
        ?>

    </tbody>

</table>


<?php
//QUERY TO DELETE COMMENTS

if(isset($_GET['delete'])){

    $the_user_id = $_GET['delete'];
    $sql = "DELETE FROM users WHERE user_id = {$the_user_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: comments.php?error=sql");
        exit();
    }

    header("Location: users.php?input=Delete");    
}

?>

<?php
//change user role to admin (to grant admin privileges)

if(isset($_GET['change_to_admin'])){

    $the_user_id = $_GET['change_to_admin'];
    
    $sql = "UPDATE users SET user_role='Admin' WHERE user_id = {$the_user_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: users.php?error=sql");
        exit();
    }

    header("Location: users.php?input=admin");    
}

?>

<?php
////change user role to user

if(isset($_GET['change_to_subscriber'])){

    $the_user_id = $_GET['change_to_subscriber'];
    
    $sql = "UPDATE users SET user_role='Subscriber' WHERE user_id = {$the_user_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: users.php?error=sql");
        exit();
    }

    header("Location: users.php?input=subscriber");    
}

?>













