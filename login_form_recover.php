<!----------Login Form------------>
<!-- Users will be re-directed here after reseting password -->

<?php  include "includes/connect.php"; ?>
<?php  include "includes/header.php"; ?>


<!-- Navigation -->
<?php  include "includes/navigation.php"; ?>


<!-- Page Content -->
<div class="container">

    <?php
    //success message
    if (isset($_GET['success'])) {
        if ($_GET['success'] == 'change') {
            echo "<p style='color:green;font-size:25px;text-align:center'>Password Reseted Successfully!<p>";
        }
    }
    ?>


    <div class="form-gap"></div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                            <h3><i class="fa fa-user fa-4x"></i></h3>
                            <h2 class="text-center">Login</h2>
                            <div class="panel-body">


                                <form id="login-form" action="includes/login.php" role="form" autocomplete="off" class="form" method="post">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-user color-blue"></i></span>
                                            <input name="username" type="text" class="form-control" placeholder="Enter your Username">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                            <input name="password" type="password" class="form-control" placeholder="Enter your New Password">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" name="login_submit" class="form-control btn btn-primary">LOG IN</button>
                                    </div>

                                </form>


                            </div><!-- Body-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <?php include "includes/footer.php";?>

</div> <!-- /.container -->
