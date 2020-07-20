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
        if ($_GET['input'] == 'success') {
            echo "<p style='color:green;font-size:25px'>User Added!<p>";
        } elseif ($_GET['input'] == 'Delete') {
            echo "<p style='color:green;font-size:25px'>User Deleted!<p>";
        } elseif ($_GET['input'] == 'admin') {
            echo "<p style='color:green;font-size:25px'>Admin Priviliges Granted!<p>";
        }
        elseif ($_GET['input'] == 'subscriber') {
            echo "<p style='color:green;font-size:25px'>Role changed to Subscriber!<p>";
        }
        elseif ($_GET['input'] == 'update') {
            echo "<p style='color:green;font-size:25px'>User Information Updated!<p>";
        }
    }
    ?>


    <div id="page-wrapper">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">

                <div class="col-lg-12">

                    <h1 class="page-header">
                        USERS
                        <small>CRUD operations</small>
                    </h1>

                    <?php
                    //Including Pages based on condition technique (new)

                    if(isset($_GET['source'])){
                        $source = $_GET['source'];

                        switch($source){

                                //add user page
                            case 'add_user':
                                include "includes/add_user.php";
                                break;

                                //edit user page
                            case 'edit_user':
                                include "includes/edit_user.php";
                                break;

                        }
                    }
                    else{
                        include "includes/view_all_users.php";
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