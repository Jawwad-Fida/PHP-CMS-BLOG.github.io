<?php
//we will make this page dynamic - i.e. it will change according to data
include "../includes/connect.php";
include "includes/admin_header.php";
include "functions.php";

?>

<div id="wrapper">


    <?php
    //Navigation

    //we will make this page dynamic - i.e. it will change according to data
    include "includes/admin_navigation.php";
    ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">

                    <h1 class="page-header">
                        Welcome to the Admin Panel (<?php echo $_SESSION['user_name'];?>)
                        <small><?php echo $_SESSION['role'];?></small>
                    </h1>


                    <?php
                    //success messages
                    if (isset($_GET['success'])) {
                        if ($_GET['success'] == 'login') {
                            echo "<p style='color:green;font-size:30px;text-align:center'>Login Successfull!<p>";
                        }   
                    }
                    ?>


                </div>
            </div>
            <!-- /.row -->


            <!-- Copy paste all the code from admin_widgets.html to here (DASHBOARD) -->
            <!-- /.row -->

            <!----------------------- POST WIDGETS ---------------------------->
            <div class="row">

                <h2 style="text-align:center;color:brown;font-size:30px;font-weight:bold;text-decoration:underline">All POSTS in the Blog</h2>

                <!-- ALL POSTS-->
                
                <!-- change col-lg-3 to make the boxes bigger-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i> <!-- fa = font-awesome is a library for boostrap -->
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $sql = "SELECT * FROM posts";
                                    $result = mysqli_query($conn,$sql);

                                    $post_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$post_count}</div>";
                                    ?>

                                    <div>Posts</div>
                                </div>

                            </div>
                        </div>

                        <a href="posts.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- DRAFT POSTS-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i> <!-- fa = font-awesome is a library for boostrap -->
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $query = "SELECT * FROM posts WHERE post_status = 'draft'";
                                    $result = mysqli_query($conn,$query);
                                    $post_draft_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$post_draft_count}</div>";
                                    ?>

                                    <div>Draft Posts</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- PUBLISHED POSTS-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i> <!-- fa = font-awesome is a library for boostrap -->
                                </div>


                                <div class="col-xs-9 text-right">

                                    <?php
                                    $query = "SELECT * FROM posts WHERE post_status = 'published'";
                                    $result = mysqli_query($conn,$query);
                                    $post_published_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$post_published_count}</div>";
                                    ?>

                                    <div>Published Posts</div>
                                </div>


                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!----------------------- COMMENT WIDGETS ---------------------------->
            <div class="row">

                <h2 style="text-align:center;color:brown;font-size:30px;font-weight:bold;text-decoration:underline">All COMMENTS in the Blog</h2>

                <!-- ALL COMMENTS-->
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $sql = "SELECT * FROM comments";
                                    $result = mysqli_query($conn,$sql);
                                    $comment_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$comment_count}</div>";
                                    ?>

                                    <div>Comments</div>
                                </div>

                            </div>
                        </div>

                        <a href="comments.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- PENDING COMMENTS-->
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $query = "SELECT * FROM comments WHERE comment_status = 'Pending'";
                                    $result = mysqli_query($conn,$query);
                                    $pending_comment_count = mysqli_num_rows($result);


                                    echo  "<div class='huge'>{$pending_comment_count}</div>";
                                    ?>

                                    <div>Pending Comments</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- UNAPPROVED COMMENTS-->
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $query = "SELECT * FROM comments WHERE comment_status = 'Unapproved'";
                                    $result = mysqli_query($conn,$query);
                                    $unapproved_comment_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$unapproved_comment_count}</div>";
                                    ?>

                                    <div>Unapproved Comments</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- APPROVED COMMENTS-->
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $query = "SELECT * FROM comments WHERE comment_status = 'Approved'";
                                    $result = mysqli_query($conn,$query);
                                    $approved_comment_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$approved_comment_count}</div>";
                                    ?>

                                    <div>Approved Comments</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!----------------------- USER WIDGETS ---------------------------->
            <div class="row">

                <h2 style="text-align:center;color:brown;font-size:30px;font-weight:bold;text-decoration:underline">All USERS in the Blog</h2>

                <!-- ALL USERS-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php
                                    $sql = "SELECT * FROM users";
                                    $result = mysqli_query($conn,$sql);
                                    $user_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$user_count}</div>";
                                    ?>

                                    <div>Users</div>
                                </div>

                            </div>
                        </div>

                        <a href="users.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>

                    </div>
                </div>

                <!-- ADMINS-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <?php

                                    $query = "SELECT * FROM users WHERE user_role = 'Admin'";
                                    $result = mysqli_query($conn,$query);
                                    $admin_count = mysqli_num_rows($result);


                                    echo  "<div class='huge'>{$admin_count}</div>";
                                    ?>

                                    <div>Admins</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- SUBSCRIBERS-->
                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">
                                    <?php
                                    $query = "SELECT * FROM users WHERE user_role = 'Subscriber'";
                                    $result = mysqli_query($conn,$query);
                                    $subscriber_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$subscriber_count}</div>";
                                    ?>

                                    <div>Subscribers</div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>


            <!----------------------- CATEGORY WIDGETS ---------------------------->
            <div class="row">

                <h2 style="text-align:center;color:brown;font-size:30px;font-weight:bold;text-decoration:underline"> CATEGORIES </h2>

                <div class="col-lg-4 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>


                                <div class="col-xs-9 text-right">

                                    <?php
                                    $sql = "SELECT * FROM categories";
                                    $result = mysqli_query($conn,$sql);
                                    $category_count = mysqli_num_rows($result);

                                    echo  "<div class='huge'>{$category_count}</div>";
                                    ?>

                                    <div>Categories</div>
                                </div>

                            </div>
                        </div>

                        <a href="categories.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>

                    </div>
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



    <!--------------  NOTIFICATIONS -------------->


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
    <script src="https://js.pusher.com/6.0/pusher.min.js"></script>

    <script>

        $(document).ready(function(){

            var pusher = new Pusher('285ccf2680cbed6bb75d',{

                cluster: 'ap2',
                encrypted: true

            });

            var notificationChannel = pusher.subscribe('notifications');
            notificationChannel.bind('new_user',function(notification){

                var message = notification.message;

                //toastr cdn library - ES^
                toastr.success(`${message} just registered`);

                console.log(message);

            });

        });

    </script>










