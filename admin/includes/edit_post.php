<?php

if(isset($_GET['p_id'])){

    //catch value from url
    $the_post_id = $_GET['p_id'];

    //get data from database
    $sql = "SELECT * FROM posts WHERE post_id={$the_post_id}";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $post_author = $row['post_author'];
        $post_title = $row['post_title'];
        $post_cat_title = $row['post_category_title'];
        $post_image = $row['post_image'];
        $post_tags = $row['post_tags'];              
        $post_content = $row['post_content'];
        $post_status = $row['post_status'];
    }

}
//pass value into the fields(form) from database by using value="" in <input>

?>

<?php

if(isset($_POST['edit_post'])){

    $post_title = validate($_POST['title']);
    $post_title = mysqli_real_escape_string($conn,$post_title);

    $post_category_title = validate($_POST['post_category_title']);
    $post_category_title = mysqli_real_escape_string($conn,$post_category_title);

    $post_author = validate($_POST['author']);
    $post_author = mysqli_real_escape_string($conn,$post_author);

    $post_status = validate($_POST['post_status']);
    $post_status = mysqli_real_escape_string($conn,$post_status);

    $post_tags = validate($_POST['post_tags']);
    $post_tags = mysqli_real_escape_string($conn,$post_tags);

    $post_content = validate($_POST['post_content']);
    $post_content = mysqli_real_escape_string($conn,$post_content);

    //current date will be added
    $post_date = date("d-m-Y");

    $post_image = $_FILES['image']['name'];
    $post_image_temp = $_FILES['image']['tmp_name'];
    $fileError = $_FILES['image']['error'];
    $fileSize = $_FILES['image']['size'];

    upload_image($post_image,$post_image_temp,$fileError,$fileSize);

    //make sure the image value is not empty (because update might lose the image)
    if(empty($post_image)){
        $sql = "SELECT * FROM posts WHERE post_id ={$the_post_id}";
        $result = mysqli_query($conn,$sql);

        while($row = mysqli_fetch_assoc($result)){
            $post_image = $row['post_image'];
        }

    }

    //UPDATE QUERY
    $sql = "UPDATE posts
    SET post_category_title=?,post_title=?, post_author=?,post_date=?,post_image=?,post_content=?,post_tags=?,post_status=?
    WHERE post_id=?";   

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt,$sql)){
        header("Location: posts.php?error=sql");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ssssssssi",$post_category_title,$post_title,$post_author,$post_date,$post_image,$post_content,$post_tags,$post_status,$the_post_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: posts.php?input=update&post_id={$the_post_id}");

}

?>


<!-- Creating Post HTML form in admin -->
<h1 style="text-align:center;font-weight:bold;color:brown">EDIT POSTS</h1>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input type="text" value="<?php echo $post_title;?>" class="form-control" name="title">
    </div>

    <div class="form-group">
        <label for="title">Post Author</label>
        <input type="text" value="<?php echo $post_author;?>" class="form-control" name="author">
    </div>


    <div class="form-group">  
        <label for="category title">Post Category title</label>
        <select name="post_category_title" id="post_category">
            <!-- If any option is not selected, default is chosen-->
            <?php
            //Display all categories from the database

            //using categories table (cat_title) to relate with posts table (post_category_title) (LINK = relation table)

            $sql = "SELECT * FROM categories";
            $result= mysqli_query($conn,$sql); 

            while($row = mysqli_fetch_assoc($result)){
                $cat_title = $row['cat_title'];

                if($cat_title == $post_cat_title){
                    //selected attribute -  specifies that an option should be pre-selected when the page loads.
                    //The pre-selected option will be displayed first in the drop-down list.
                    
                    echo "<option selected value='$cat_title'>{$cat_title}</option>";
                }
                else{
                    echo "<option value='$cat_title'>{$cat_title}</option>";
                }

            }
            ?>

        </select>
    </div>


    <div class="form-group">
        <label for="status">Post Status</label>
        <select name="post_status" id="">
            <!-- If any option is not selected, default is chosen-->
            <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>

            <?php
            //adding select options to post status

            if($post_status === 'draft'){
                //if post status is draft, give option to change to published
                echo "<option value='published'>Published</option>"; 
            }
            else{
                //if post status is published, give option to change to draft
                echo "<option value='draft'>Draft</option>";
            }
            ?>

        </select>
    </div>

    <!-- Get the image dynamically -->
    <div class="form-group">
        <img width="100" src="../images/<?php echo $post_image; ?>">
        <input type="file"  name="image">
    </div>

    <div class="form-group">
        <label for="post_tags">Post Tags</label>
        <input type="text" value="<?php echo $post_tags;?>" class="form-control" name="post_tags">
    </div>

    <div class="form-group">
        <label for="post_content">Post Content</label>
        <textarea id="editor" class="form-control" name="post_content" id="body" cols="30" rows="10">
            <?php echo htmlspecialchars($post_content); //dynamic content in text area?>
        </textarea>
    </div>


    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_post" value="Update Post">
    </div>

</form>



