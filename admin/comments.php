<?php
//we will make this page dynamic - i.e. it will change according to data

include "../includes/connect.php";
include "includes/admin_header.php";
include "functions.php";

?>


<div id="wrapper">

    <?php
    include "includes/admin_navigation.php";
    ?>

    <?php
    //error messages
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'sql') {
            echo "<p style='color:red;font-size:25px'>Error connecting to database!<p>";
        }
    }
    ?>

    <?php
    //success messages
    if (isset($_GET['input'])) {
        if ($_GET['input'] == 'UNapprove') {
            echo "<p style='color:green;font-size:25px'>Comment Unapproved!<p>";
        } elseif ($_GET['input'] == 'Delete') {
            echo "<p style='color:green;font-size:25px'>Comment Deleted!<p>";
        } elseif ($_GET['input'] == 'APprove') {
            echo "<p style='color:green;font-size:25px'>Comment Approved!<p>";
        }
    }
    ?>

    <div id="page-wrapper">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header">
                        COMMENTS
                        <small>CRUD operations</small>
                    </h1>

                    <?php
                    
                    include "includes/view_all_comments.php";

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