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
        if ($_GET['error'] == 'empty') {
            echo "<p style='color:red;font-size:25px'>You cannot leave this field empty!<p>";
        }
        elseif ($_GET['error'] == 'sql') {
            echo "<p style='color:red;font-size:25px'>Error in SQL!<p>";
        }
        elseif ($_GET['error'] == 'size') {
            echo "<p style='color:red;font-size:25px'>Category has to be more than 2 characters!<p>";
        }
    }
    ?>

    <?php
    //success messages
    if (isset($_GET['input'])) {
        if ($_GET['input'] == 'success') {
            echo "<p style='color:green;font-size:25px'>New Category Added!<p>";
        } elseif ($_GET['input'] == 'Delete') {
            echo "<p style='color:green;font-size:25px'>Category Deleted!<p>";
        } elseif ($_GET['input'] == 'update') {
            echo "<p style='color:green;font-size:25px'>Category Updated!<p>";
        }
    }
    ?>

    <div id="page-wrapper">
        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">
                        Categories Page
                        <small>CRUD operations</small>
                    </h1>


                    <!-- Boostrap class to build column, 6 = half of screen -->
                    <div class="col-xs-6">

                        <?php
                        //INSERT CATEGORIES
                        insert_categories(); 
                        ?>
                        

                        <!-- Form to insert data into database -->
                        <form action="categories.php" method="post">

                            <div class="form-group">
                                <label for="cat_title">Add Category</label>
                                <input class="form-control" type="text" name="cat_title">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="submit">Insert Category</button>
                            </div>

                        </form>

                       
                        <?php
                        //UPDATE CATEGORIES
                        if (isset($_GET['edit'])) {

                            //include the file to update categories in table
                            //NOTE: - since updating uses id, we have to check and collect the id from url
                            $cat_id = $_GET['edit'];
                            include "includes/admin_updateCategory.php";
                        }
                        ?>

                    </div>


                    <div class="col-xs-6">

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Category Title</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php
                                //FIND ALL CATEGORIES 
                                //AFTER FINDING CATEGORIES, CREATE LINKS TO UPDATE OR DELETE THEM
                                findAllCategories();
                                ?>
                                
                                <?php
                                //DELETE CATEGORIES 
                                //BY GRABBING ID FROM URL SENT FROM findAllCategories()
                                delete_categories();
                                ?>

                            </tbody>

                        </table>
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