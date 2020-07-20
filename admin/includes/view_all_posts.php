<?php

//validating radio button values
if(isset($_POST['checkBoxArray'])){
    $check_array = $_POST['checkBoxArray'];

    //loop through the array - use foreach loop
    //foreach(array_name as any_variable_name) -> any_variable_name will hold array data as loop iterates

    foreach($check_array as $post_value_id){
        //post_id value is being sent by the checkboxes

        $bulk_options = $_POST['bulk_options'];
        //since we can receive 3 values anytime, for comparison use switch-case

        switch($bulk_options){

            case 'published':

                $sql = "UPDATE posts SET post_status='{$bulk_options}' WHERE post_id={$post_value_id}";
                $result = mysqli_query($conn,$sql);

                if(!$result){
                    header("Location: posts.php?error=sql");
                    exit();
                }

                echo "<p style='color:green;font-size:25px;text-align:center'>Post number {$post_value_id} is now Published!</p>";
                break;

            case 'draft':
                $sql = "UPDATE posts SET post_status='{$bulk_options}' WHERE post_id={$post_value_id}";
                $result = mysqli_query($conn,$sql);

                if(!$result){
                    header("Location: posts.php?error=sql");
                    exit();
                }

                echo "<p style='color:brown;font-size:25px;text-align:center'>Post number {$post_value_id} is now Draft!</p>";
                break;

            case 'delete':
                $sql = "DELETE FROM posts WHERE post_id={$post_value_id}";
                $result = mysqli_query($conn,$sql);

                if(!$result){
                    header("Location: posts.php?error=sql");
                    exit();
                }

                echo "<p style='color:blue;font-size:25px;text-align:center'>Post number {$post_value_id} is now Deleted!</p>";
                break;

            case 'clone':
                //clone posts

                //get the posts based on the checkbox
                $sql = "SELECT * FROM posts WHERE post_id = {$post_value_id} ";
                $result= mysqli_query($conn, $sql);

                while ($row = mysqli_fetch_array($result)) {
                    $post_title = $row['post_title'];
                    $post_category_title = $row['post_category_title'];
                    $post_date  = $row['post_date']; 
                    $post_author = $row['post_author'];
                    $post_status = $row['post_status'];
                    $post_image  = $row['post_image'] ; 
                    $post_tags = $row['post_tags']; 
                    $post_content = $row['post_content'];

                }

                $sql = "INSERT INTO posts(post_category_title, post_title, post_author,post_date,post_image,post_content,post_tags,post_status) VALUES(?,?,?,?,?,?,?,?);";   

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    header("Location: posts.php?error=sql");
                    exit();
                }

                mysqli_stmt_bind_param($stmt,"ssssssss",$post_category_title,$post_title,$post_author,$post_date,$post_image,$post_content,$post_tags,$post_status);
                mysqli_stmt_execute($stmt);
                echo "<p style='color:green;font-size:25px;text-align:center'>Post number {$post_value_id} Cloned!</p>";
                mysqli_stmt_close($stmt);
                break;         

        }

    }

}

?>


<!-- Adding bulk options to POST -->
<form action="" method="post">

    <!-- Build a TABLE to display all posts-->
    <table class='table table-bordered table-hover'>

        <div class="col-xs-4">
            <select class="form-control" name="bulk_options">
                <option value="">Select Options</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
            </select>
        </div>

        <div class="col-xs-4">

            <input type="submit" name="submit" class="btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New Post</a>

        </div>

        <br>
        <br>

        <!-- DISPLAYING ALL POSTS -->
        <thead>
            <tr>
                <!-- we will select posts using radio buttons -->
                <th><input id="selectAllBoxes" type="checkbox"></th>

                <th>Id</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category Title</th>
                <th>Status</th>  
                <th>Image</th>  
                <th>Tags</th>  
                <th>Comments</th>  
                <th>Date</th>
                <th>Views</th>   
                <th>Delete</th>  
                <th>Edit</th>  
                <th>Reset Views</th>  
            </tr>
        </thead>

        <tbody>

            <?php

            $user = currentUser();

            //display the new posts first

            //$sql = "SELECT * FROM posts WHERE post_user={$user} ORDER BY post_id DESC"; // Display posts based on user creation 

            //QUERY TO JOIN TABLES 

            if(is_admin()){
                
                $sql_join = "SELECT posts.post_id,posts.post_category_title,posts.post_title,posts.post_author,posts.post_date,posts.post_image,posts.post_content,posts.post_tags,posts.post_comment_count,posts.post_status,posts.post_views_count, ";
                $sql_join .= "categories.cat_id,categories.cat_title "; //join (concan) to single line using .=
                $sql_join .= "FROM posts LEFT JOIN categories ON posts.post_category_title = categories.cat_title ORDER BY posts.post_id DESC";

            }
            else{
                
                $sql_join = "SELECT posts.post_id,posts.post_category_title,posts.post_title,posts.post_author,posts.post_date,posts.post_image,posts.post_content,posts.post_tags,posts.post_comment_count,posts.post_status,posts.post_views_count, ";
                $sql_join .= "categories.cat_id,categories.cat_title "; //join (concan) to single line using .=
                $sql_join .= "FROM posts LEFT JOIN categories ON posts.post_category_title = categories.cat_title WHERE post_author='{$_SESSION['user_name']}' ORDER BY posts.post_id DESC";
            }


            $result = mysqli_query($conn, $sql_join);

            while ($row = mysqli_fetch_assoc($result)) {
                // echo "<tr>";
                $post_id = $row['post_id'];
                $post_author = $row['post_author'];
                $post_title = $row['post_title'];
                $post_category_title = $row['post_category_title'];
                $post_status = $row['post_status']; 
                $post_image = $row['post_image'];
                $post_tags = $row['post_tags'];
                $post_comment = $row['post_comment_count'];         
                $post_date = $row['post_date'];
                $post_views_count = $row['post_views_count'];

                //AFTER JOIN TABLES
                $category_title = $row['cat_title'];

                //close off php
            ?>

            <tr>
                <td><input class="checkBoxes" type="checkbox" name="checkBoxArray[]" value="<?php echo $post_id; ?>"></td>
                <!-- Fill the empty array with the post_id of the posts we want to delete-->

                <!-- the way we find each value of the checkbox is when we click it.  -->

                <td><?php echo $post_id; ?></td>

                <!-- clicking on these links will re-direct us to targeted post/author from admin panel -->

                <td><a target="_blank" href="../post_author.php?p_author=<?php echo $post_author; ?>"><?php echo $post_author; ?></a></td>

                <td><a target="_blank" href="../post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a></td>

                <!-- <td><?php //echo $post_category_title; ?></td> -->
                <td><?php echo $category_title; ?></td>

                <td><?php echo $post_status; ?></td>
                <td><img width="100px" src="../images/<?php echo $post_image; //go back twice to root folder?>" alt="image"></td>
                <td><?php echo $post_tags; ?></td>
                <td><?php echo $post_comment; ?></td>
                <td><?php echo $post_date; ?></td>
                <td><?php echo $post_views_count; ?></td>

                <!-- PASS parameters to url -->

                <!-- Create Link to delete posts, on click-->
                <td><a onClick='deleteme(<?php echo $post_id; ?>)' href="" class="btn btn-danger">Delete</a></td>

                <!-- Create Link to edit posts, on click-->
                <td><a class="btn btn-success" href="posts.php?source=edit_post&p_id=<?php echo $post_id;?>">Edit</a></td>

                <td><a class="btn btn-warning" href="posts.php?reset=<?php echo $post_id;?>">Reset</a></td>

            </tr>

            <?php
                //open up php, and close loop
            }
            ?>

            <!-- JavaScript onclick confirm on gridview delete -->
            <script language="javascript">
                function deleteme(the_post_id){
                    if(confirm("Do you want to delete: ")){
                        window.location.href="posts.php?delete=" +the_post_id;
                    }
                }
            </script>

        </tbody>

    </table>
</form>


<?php
//QUERY TO DELETE POSTS

if(isset($_GET['delete'])){

    $the_post_id = $_GET['delete'];
    $sql = "DELETE FROM posts WHERE post_id = {$the_post_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: posts.php?error=sql");
        exit();
    }

    header("Location: posts.php?input=Delete");    
}

?>

<?php
//QUERY TO RESET VIEWS OF POSTS

if(isset($_GET['reset'])){

    $the_post_id = $_GET['reset'];
    $count=0;

    $sql = "UPDATE posts SET post_views_count={$count} WHERE post_id={$the_post_id}";
    $result = mysqli_query($conn,$sql);

    if (!$result) {
        header("Location: posts.php?error=sql");
        exit();
    }

    header("Location: posts.php?input=RESET");    
}

?>


























