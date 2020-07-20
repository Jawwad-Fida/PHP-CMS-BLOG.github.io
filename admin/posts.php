<?php
//we will make this page dynamic - i.e. it will change according to data

include "../includes/connect.php";
include "includes/admin_header.php";
include "functions.php";

// ../ = go back twice to root folder
// ./ = go back one folder
?>


<div id="wrapper">

    <?php
    include "includes/admin_navigation.php";
    ?>


    <div id="page-wrapper">
        <div class="container-fluid">


            <?php
            //error messages
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'sql') {
                    echo "<p style='color:red;font-size:40px;text-align:center'>Error connecting to database!<p>";
                }
            }
            ?>

            <?php
            //success messages
            if (isset($_GET['input'])) {
                if ($_GET['input'] == 'success') {
                    echo "<p style='color:green;font-size:40px;text-align:center'>New Post Added!</p>";
                    
                    $the_post_id = $_GET['post_id'];
                    
                    echo "<p style='color:blue;font-size:25px;text-align:center'><a target='_blank' href='../post.php?p_id={$the_post_id}'>Click here to view the Added Post</a></p>";
                    
                } elseif ($_GET['input'] == 'Delete') {
                    echo "<p style='color:green;font-size:40px;text-align:center'>Post Deleted!</p>";
                } elseif ($_GET['input'] == 'update') {
                    
                    echo "<p style='color:green;font-size:40px;text-align:center'>Post Updated!</p>";
                   
                    $the_post_id = $_GET['post_id']; 
                    
                    echo "<p style='color:blue;font-size:25px;text-align:center'><a target='_blank' href='../post.php?p_id={$the_post_id}'>Click here to view the Edited Post</a></p>";
                }
                elseif ($_GET['input'] == 'RESET') {
                    echo "<p style='color:green;font-size:40px;text-align:center'>Views Reseted!</p>";
                } 
            }
            ?>


            <!-- Page Heading -->
            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header">
                        POSTS
                        <small>CRUD operations</small>
                    </h1>

                    <?php
                    //Including Pages based on condition technique (new)

                    if(isset($_GET['source'])){
                        $source = $_GET['source'];

                        switch($source){

                                //add post page
                            case 'add_post':
                                include "includes/add_post.php";
                                break;

                                //edit post page
                            case 'edit_post':
                                include "includes/edit_post.php";
                                break;

                        }
                    }
                    else{
                        include "includes/view_all_posts.php";
                    }

                    ?>

                </div>


            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

    <?php
    include "includes/admin_footer.php";
    ?>