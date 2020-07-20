<!-- Build a TABLE to display all posts-->
<table class='table table-bordered table-hover'>

    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>  
            <th>In Response to Post ID</th>  
            <th>In Response to Post Title</th>  
            <th>Date</th>  
            <th>Approve</th>  
            <th>Unapprove</th>  
            <th>Delete</th>  
        </tr>
    </thead>

    <tbody>       

        <?php
        //recent comments are shown first - ordered in descending order
        if(is_admin()){
            $sql = "SELECT * FROM comments ORDER BY comment_id DESC";
            $result = mysqli_query($conn, $sql);
        }
        else{
            $sql = "SELECT * FROM comments WHERE comment_post_author='{$_SESSION['user_name']}' ORDER BY comment_id DESC";
            $result = mysqli_query($conn, $sql);
        }


        while ($row = mysqli_fetch_assoc($result)) {
            $comment_id = $row['comment_id'];
            $comment_post_id = $row['comment_post_id']; //here, we saved the post id when we submitted the comment form
            $comment_post_title = $row['comment_post_title']; //here, we saved the post title when we submitted the comment form
            $comment_user = $row['comment_user'];
            $comment_content = $row['comment_content'];
            $comment_email = $row['comment_email'];
            $comment_status = $row['comment_status'];    
            $comment_date = $row['comment_date'];

            //close off php
        ?>

        <tr>
            <td><?php echo $comment_id; ?></td>
            <td><?php echo $comment_user; ?></td>
            <td><?php echo $comment_content; ?></td>
            <td><?php echo $comment_email; ?></td> 
            <td><?php echo $comment_status; ?></td>

            <td><a target="_blank" href="../post.php?p_id=<?php echo $comment_post_id?>"><?php echo $comment_post_id; ?></a></td>

            <td><a target="_blank" href="../post.php?p_id=<?php echo $comment_post_id?>"><?php echo $comment_post_title; ?></a></td>

            <td><?php echo $comment_date; ?></td>

            <!-- PASS parameters to url -->

            <!-- Proof links to show or not show comments-->
            <td><a class="btn btn-primary" href="comments.php?approve=<?php echo $comment_id;?>">Approve</a></td>

            <td><a class="btn btn-primary" href="comments.php?unapprove=<?php echo $comment_id;?>">Unapprove</a></td>

            <!-- Create Link to delete comments, on click-->
            <td><a class="btn btn-danger" href="comments.php?delete=<?php echo $comment_id;?>">Delete</a></td>

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

    $the_comment_id = $_GET['delete'];
    $sql = "DELETE FROM comments WHERE comment_id = {$the_comment_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: comments.php?error=sql");
        exit();
    }

    header("Location: comments.php?input=Delete");    
}

?>

<?php
//UNAPPROVE COMMENTS - Dont show those comments

if(isset($_GET['unapprove'])){

    $the_comment_id = $_GET['unapprove'];

    $sql = "UPDATE comments SET comment_status='Unapproved' WHERE comment_id = {$the_comment_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: comments.php?error=sql");
        exit();
    }

    header("Location: comments.php?input=UNapprove");    
}

?>

<?php
//APPROVE COMMENTS - show those comments

if(isset($_GET['approve'])){

    $the_comment_id = $_GET['approve'];

    $sql = "UPDATE comments SET comment_status='Approved' WHERE comment_id = {$the_comment_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: comments.php?error=sql");
        exit();
    }

    header("Location: comments.php?input=APprove");    
}

?>













