<!-- Blog Sidebar Widgets Column -->
<div class="col-md-4">


    <!-- ------Search Area --------- -->
    <!-- Blog Search Well --> <!-- Search Engine -->
    <div class="well">
        <h4 style='text-align:center;font-size:20px'>Blog Search</h4>

        <!-- Create form to search data in database -->
        <form action="search.php" method="post">
            <div class="input-group">
                <input name="search" type="text" class="form-control">
                <span class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>
                </span>
            </div>
            <!-- /.input-group -->
        </form>
    </div>



    <?php
    //Depending on role, we will display the login form for admin

    if(isset($_SESSION['role'])){

        if($_SESSION['role']){
            //when a user logs in, and goes to home page, THE LOGIN FORM WILL BE HIDDEN (until he logs out)

            $username = $_SESSION['user_name'];
            
            echo "<div class='well'>
           <h4 style='text-align:center;font-size:20px;font-weight:bold'>Logged in as {$username}</h4>
           
           <a style='display: block; margin: 0 auto' href='admin/includes/logout.php' class='btn btn-primary'>Logout</a>

           </div>";      
           
        }
    }
    else{
        //on home page, initially there is no session, so display login form for admin
        include "login_form.php";
    }
    ?>


    <!-- ------Categories Area --------- -->
    <!-- Blog Categories Well -->
    <div class="well">
        <h4 style='text-align:center;font-size:20px'>Blog Categories</h4>

        <?php
        $sql="SELECT * FROM categories"; 
        $result = mysqli_query($conn,$sql);
        ?>

        <div class="row">
            <div class="col-lg-12"> <!-- 12=full, 6=half [column grid system using boostrap] -->  

                <ul class="list-unstyled">

                    <?php
                    while($row = mysqli_fetch_assoc($result)){
                        $cat_title = $row['cat_title'];
                        //link => click on categories link on sidebar
                        echo "<li><a href='category.php?category={$cat_title}'>{$cat_title}</a></li>";
                    }
                    ?>

                </ul>

            </div>

        </div>
        <!-- /.row -->
    </div>


    <!-- Side Widget Well -->
    <?php
    include "widget.php";   
    ?>

</div>

