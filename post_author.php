<?php
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
            //catch the id from url
            
            if(isset($_GET['p_author'])){
                $the_post_author = $_GET['p_author'];  
            }
            
            //using SQL LIKE -> for keywords
            $sql="SELECT * FROM posts WHERE post_author LIKE '%$the_post_author%'";
            $result = mysqli_query($conn,$sql);
            
            $rowNumber = mysqli_num_rows($result);
            echo "<h1 style='color:green;text-align:center'>There are " .$rowNumber ," results</h1>";

            while($row = mysqli_fetch_assoc($result)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_author = $row['post_author'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];
                $post_content = substr($row['post_content'],0,250); //shrink content

                //loop to keep picking up data from database
                //turn off php - (turn off loop)
            ?> 

            <!-- DISPLAYING DYNAMIC DATA -->

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            
            <!-- Clicking on heading will re-dirtect to post page -->
            <h2>
                <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
            </h2>
            
            <p class="lead">
                by <a href="#"><?php echo $post_author ?></a>
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
