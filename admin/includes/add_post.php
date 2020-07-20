<?php
//error messages
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'emptyfields') {
        echo "<p style='color:red;font-size:25px'>Please Fill in all the Fields!<p>";
    }
}
?>


<?php

if(isset($_POST['submit_post'])){

    $post_title = validate($_POST['title']);
    $post_title = mysqli_real_escape_string($conn,$post_title);

    $post_author = validate($_POST['author']);
    $post_author = mysqli_real_escape_string($conn,$post_author);

    $post_category_title = validate($_POST['post_category']);
    $post_category_title = mysqli_real_escape_string($conn,$post_category_title);

    $post_status = validate($_POST['post_status']);
    $post_status = mysqli_real_escape_string($conn,$post_status);

    //For Files, we need $_FILES['form_name']['property']
    //FILES have 5 properties, we are taking 2
    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $fileError = $_FILES['image']['error'];
    $fileSize = $_FILES['image']['size'];
    //NOTE: - files when uploaded will be sent to a temporary location. We move it from the temporary location -> to the location we want.

    $post_tags = validate($_POST['post_tags']);
    $post_tags = mysqli_real_escape_string($conn,$post_tags);

    $post_content = validate($_POST['post_content']);
    $post_content = htmlspecialchars_decode(mysqli_real_escape_string($conn,$post_content));
    $post_content = str_replace("<p>"," ",$post_content);

    //current date will be added
    $post_date = date("d-m-Y");
    $post_comment_count = 0; //initially 0 comments when a post is created

    //UPLOAD FILE
    upload_image($post_image,$post_image_temp,$fileError,$fileSize); //function call 

    if(empty($post_title) || empty($post_author) || empty($post_tags) || empty($post_content)){
        header("Location: posts.php?source=add_post&error=emptyfields"); 
        exit();
    }

    //QUERY

    $sql = "INSERT INTO posts(post_category_title, post_title, post_author,post_date,post_image,post_content,post_tags,post_comment_count,post_status) VALUES(?,?,?,?,?,?,?,?,?);";   

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: posts.php?error=sql");
        exit();
    }

    mysqli_stmt_bind_param($stmt,"sssssssss",$post_category_title,$post_title,$post_author,$post_date,$post_image,$post_content,$post_tags,$post_comment_count,$post_status);
    mysqli_stmt_execute($stmt);

    //PULL THE LAST CREATED (RECORD) ID - using mysqli_insert_id()

    $the_post_id = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);
    header("Location: posts.php?input=success&post_id={$the_post_id}");

}

?>


<!-- Creating Post HTML form in admin -->

<!-- action="" means same page, # means default[no change] -->
<h1 style="text-align:center;font-weight:bold;color:brown">ADD POSTS</h1>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" class="form-control" name="title">
    </div>

    <div class="form-group">  
        <label for="Author">Post Author</label>
        <select name="author" id="post_category">
        <option value="<?php echo $_SESSION['user_name']; ?>"><?php echo $_SESSION['user_name']; ?></option>
        
        </select>
    </div>

    <div class="form-group">  
        <label for="category title">Post Category title</label>
        <select name="post_category" id="post_category">

            <?php
            //Display all categories from the database

            //using categories table (cat_title) to relate with posts table (post_category_title) (LINK = relation table)

            $sql = "SELECT * FROM categories";
            $result= mysqli_query($conn,$sql); 

            while($row = mysqli_fetch_assoc($result)){
                $cat_title = $row['cat_title'];
                echo "<option value='{$cat_title}'>{$cat_title}</option>";
            }
            ?>

        </select>
    </div>

    <div class="form-group">  
        <label for="status">Post Status</label>
        <select name="post_status" id="post_category">
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
        </select>
    </div>


    <!-- INPUT FIELD TO UPLOAD FILES (IMAGES) -->
    <div class="form-group">
        <label for="post_image">Post Image</label>
        <input type="file"  name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea id="editor" class="form-control "name="post_content" cols="30" rows="10">
        </textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="submit_post" value="Publish Post">
    </div>

</form>



