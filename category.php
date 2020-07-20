<?php
//we will make this page dynamic - i.e. it will change according to data

include "includes/connect.php";
include "includes/header.php";
?>


<!-- Navigation -->
<?php
include "includes/navigation.php";
?>


<!-- Page Content -->
<div class="container">
    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            //catch value from url
            //get data from url
            if(isset($_GET['category'])){
                $post_category_title = $_GET['category'];
            }

            //searching using keywords -> LIKE
            $sql="SELECT * FROM posts WHERE post_category_title LIKE '%$post_category_title%'";
            $result = mysqli_query($conn,$sql);

            $rowNumber = mysqli_num_rows($result);

            if($rowNumber < 1){
                echo "<h1 style='color:red;text-align:center'>There are no posts!</h1>";
                exit();

            }

            echo "<h1 style='color:green;text-align:center'>There are " .$rowNumber ," results</h1>";

            while($row = mysqli_fetch_assoc($result)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_author = $row['post_author'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_status = $row['post_status'];



                //excerpt the content on home page ((dont display all))= excerpt means shrink the content -> by substr()
                $post_content = substr($row['post_content'],0,250);
                //minimize from 0 to 250 characters

                //loop to keep picking up data from database

                //turn off php - (turn off loop)
            ?> 

            <!-- DISPLAYING DYNAMIC DATA -->

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <!-- Clicking on heading will re-dirtect to post page (pass post id to url - will be caught by GET in post page)-->
                <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
            </h2>

            <!-- Clicking on author will re-dirtect to page that will display all the authors posts-->
            <p class="lead">
                by <a href="post_author.php?p_author=<?php echo $post_author; ?>"><?php echo $post_author ?></a>
            </p>

            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
            <hr>
            <!-- create reference from database to image  -->
            <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
            <hr>
            <p><?php echo htmlspecialchars_decode($post_content); ?></p>

            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>
            <hr>


            <?php
                //turn php on again - (turn loop on again)
            }
            ?>



        </div>

        <!-- Blog Sidebar Widgets Column -->
        <?php
        include "includes/sidebar.php";
        ?>

    </div>
    <!-- /.row -->

    <hr>

    <!-- FOOTER -->
    <?php
    include "includes/footer.php";
    mysqli_close($conn);
    ?>
