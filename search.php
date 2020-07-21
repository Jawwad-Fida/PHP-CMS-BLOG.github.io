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

            function validate($data){
                $data = trim($data);
                $data = stripcslashes($data);
                $data = htmlspecialchars($data);

                return $data;
            }


            //catch form data
            if(isset($_POST['submit'])){
                $search = validate("%{$_POST['search']}%");
                $search = mysqli_real_escape_string($conn,$search);

                //search database to find information 
                //we will search for keywords -> LIKE

                //Since we are using prepared statements, % used with LIKE is already implemented above

                $sql = "SELECT * FROM posts WHERE post_tags LIKE ?";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt,$sql)){
                    die("QUERY FAILED " .mysqli_error($conn));
                }

                mysqli_stmt_bind_param($stmt,"s",$search);

                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                $count = mysqli_num_rows($result);
                if($count == 0){
                    echo "<h1>NO RESULT</h1>";
                }
                else{

                    //close to header
                    while($row = mysqli_fetch_assoc($result)){

                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];

                        //Since we need this loop to keep picking up data from database, and display it

                        //we do the following steps
                        //turn off php - (turn off loop)
            ?> 

            <!-- DISPLAYING DYNAMIC DATA -->

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <a href="#"><?php echo $post_title ?></a>
            </h2>
            <p class="lead">
                by <a href="index.php"><?php echo $post_author ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
            <hr>
            <!-- create reference from database to image  -->
            <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
            <hr>
            <p><?php echo $post_content ?></p>
            <a class="btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>
            <hr>

            <?php
                        //turn php on again - (turn loop on again)
                    }
                }
            }
            mysqli_stmt_close($stmt);
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
