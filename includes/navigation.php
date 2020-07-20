

<!-- navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

    <div class="container">


        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
            <!-- index.php changed to index due to ReWrite Engine in .htaccess-->
            <a class="navbar-brand" href="index">CMS BLOG</a>
        </div>


        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <?php
                //fetch data from categories table (to display on link)

                $sql="SELECT * FROM categories LIMIT 4;";
                $result = mysqli_query($conn,$sql);

                while($row = mysqli_fetch_assoc($result)){
                    $cat_title = $row['cat_title'];
                    echo "<li><a href='category.php?category={$cat_title}'>{$cat_title}</a></li>";
                }
                ?>

                <?php
                //rather than going to control panel to edit post, once we click on the post - an edit option will be available 

                if(isset($_SESSION['role'])){

                    if(isset($_GET['p_id'])){
                        $the_post_id = $_GET['p_id'];
                        echo "<li><a href='admin/posts.php?source=edit_post&p_id={$the_post_id}'>Edit Post</a></li>";  
                    } 
                }
                ?>

                <li><a href="admin/index.php">Control Panel</a></li> 

                <?php
                //display user registration form based on session

                if(isset($_SESSION['role'])){
                    //session available - don't display form
                }
                else{
                    //no session available - display form

                    // registration.php changed to registration due to ReWrite Engine in .htaccess
                    echo '<li><a href="registration">User Registration</a></li>';

                    //Contact Page

                    // contact.php changed to contact due to ReWrite Engine in .htaccess
                    echo '<li><a href="contact">Contact Page</a></li>';
                }
                ?>
                
                
            </ul>
        </div>


        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>