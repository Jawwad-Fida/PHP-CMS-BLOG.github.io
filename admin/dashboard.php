<?php
//we will make this page dynamic - i.e. it will change according to data
include "../includes/connect.php";
include "includes/admin_header.php";
include "functions.php";

?>

<div id="wrapper">

    <?php
    //we will make this page dynamic - i.e. it will change according to data
    include "includes/admin_navigation.php";
    ?>

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">

                    <h1 class="page-header">
                        Welcome to Dashboard (<?php echo get_username(); ?>)
                        <small><?php echo get_user_role(); ?></small>
                    </h1>

                </div>
            </div>
            <!-- /.row -->


            <!-- Copy and paste all the code from admin_widgets.html to here (DASHBOARD) -->
            <!-- /.row -->

            <div class="row">

               <!-- change col-lg-3 to make the boxes bigger-->
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i> <!-- fa = font-awesome is a library for boostrap -->
                                </div>


                                <div class="col-xs-9 text-right">

                                    <div class='huge'>

                                        <?php
                                        //Refracting code to functions.php -> recordCount($table)

                                        echo $post_count = recordCount('posts');
                                        ?>

                                    </div>

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


                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>


                                <div class="col-xs-9 text-right">

                                    <div class='huge'>

                                        <?php

                                        echo $comment_count = recordCount('comments');
                                        ?>

                                    </div>


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


                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>

                                <div class="col-xs-9 text-right">

                                    <div class='huge'>
                                        <?php

                                        echo $user_count = recordCount('users');
                                        ?>
                                    </div>

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


                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>


                                <div class="col-xs-9 text-right">
                                   
                                    <div class='huge'>
                                        <?php

                                        echo $category_count = recordCount('categories');
                                        ?>
                                    </div>

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
            <!-- End of widgets for dashboard-->


            <?php
            //GET DYNAMIC DATA FROM DATABASE TABLES TO PUBLISH ON CHARTS

            //Refracting code to functions.php -> checkUserRole($table,$column,$status)

            //POSTS

            $post_published_count = checkUserRole('posts','post_status','published');

            $post_draft_count = checkUserRole('posts','post_status','draft');

            //COMMENTS

            $pending_comment_count = checkUserRole('comments','comment_status','Pending');

            $approved_comment_count = checkUserRole('comments','comment_status','Approved');

            $unapproved_comment_count = checkUserRole('comments','comment_status','Unapproved');

            //USERS

            $subscriber_count = checkUserRole('users','user_role','Subscriber');

            $admin_count = checkUserRole('users','user_role','Admin');

            ?>


            <!-------------- USING GOOGLE API CHARTS -------------->


            <!------- FIRST CHART (POSTS) ------>
            <div class="row">

                <script type="text/javascript">
                    google.load("visualization", "1.1", {packages:["bar"]});
                    google.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Data', 'Numbers'],

                            <?php

                            $element_text = array('Total Posts','Published Posts','Draft Posts');       
                            $element_count = array($post_count,$post_published_count, $post_draft_count);

                            $size_of_array = sizeof($element_count);

                            for($i=0; $i < $size_of_array; $i++) {

                                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                                // Like ['2017', 1030],
                            }

                            ?>

                        ]);

                        var options = {
                            chart: {
                                title: 'Blog Posts',
                                subtitle: 'Classification',
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, options);
                    }
                </script>


                <div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>

            </div>
            <!------- END OF FIRST CHART ------>


            <!------- SECOND CHART (COMMENTS) ------>
            <div class="row">

                <script type="text/javascript">
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Data','Numbers'],

                            <?php

                            $element_text = array('Total Comments','Pending Comments', 'Approved Comments', 'Unapproved Comments');       
                            $element_count = array($comment_count,$pending_comment_count, $approved_comment_count,$unapproved_comment_count);

                            $size_of_array = sizeof($element_count);

                            for($i=0; $i < $size_of_array; $i++) {

                                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                            }

                            ?>
                        ]);

                        var options = {
                            chart: {
                                title: 'Comments',
                                subtitle: 'Classifications',
                            },
                            bars: 'horizontal' // Required for Material Bar Charts.
                        };

                        var chart = new google.charts.Bar(document.getElementById('barchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                </script>

                <div id="barchart_material" style="width: 'auto'; height: 500px;"></div>

            </div>
            <!------- END OF SECOND CHART ------>


            <!------- THIRD CHART (USERS) ------>
            <div class="row">

                <script type="text/javascript">
                    google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {

                        var data = google.visualization.arrayToDataTable([
                            ['Users', 'Categories'],
                            <?php

                            $element_text = array('Subscribers', 'Admin');       
                            $element_count = array($subscriber_count,$admin_count);

                            $size_of_array = sizeof($element_count);

                            for($i=0; $i < $size_of_array; $i++) {

                                echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
                            }

                            ?>
                        ]);

                        var options = {
                            title: 'Users Classification'
                        };

                        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

                        chart.draw(data, options);
                    }
                </script>

                <div id="piechart" style="width: 'auto'; height: 500px;"></div>

            </div>
            <!------- END OF THIRD CHART ------>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->


    <?php
    include "includes/admin_footer.php";
    ?>





