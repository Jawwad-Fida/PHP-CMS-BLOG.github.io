<!-- Form to update data in database -->
<form action="" method="post">
    <div class="form-group">
        <label for="cat-title">Edit Category</label>

        <?php
        //select which categories to update by clicking on link

        if(isset($_GET['edit'])){
            
            //collect data from url
            $cat_id = $_GET['edit'];

            $sql = "SELECT * FROM categories WHERE cat_id = ?";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt,$sql)){
                die("QUERY FAILED " .mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt,"i",$cat_id); //i = $cat_id is integer
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            while($row = mysqli_fetch_assoc($result)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];

                //close php tag so that we can include some HTML within the loop 
        ?>

        <!-- This Field will only appear when edit option is cliked, and id(value) is sent to url-->
        <input value="<?php echo $cat_title; ?>" type="text" class="form-control" name="cat_title">

        <?php 
            }
        } 
        ?>

        <?php   
        //code to update data in table

        if(isset($_POST['update_category'])) {

            $the_cat_title = validate($_POST['cat_title']);
            $the_cat_title = mysqli_real_escape_string($conn,$the_cat_title);
            
            //IMP PART -> $the_cat_title is from form and $cat_id is from url
            $sql = "UPDATE categories SET cat_title = ? WHERE cat_id = ? ";

            if(!mysqli_stmt_prepare($stmt,$sql)){
                die("QUERY FAILED " .mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'si', $the_cat_title, $cat_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header("Location: categories.php?input=update");

        }

        ?>



    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Update Category">
    </div>

</form>








