<?php
//we will make this page dynamic - i.e. it will change according to data

include "includes/connect.php";
include "includes/header.php";

//----------define how many posts per page for pagination-----------
$num_of_posts_per_page = 4;
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

            <!-- First Blog Post -->

            <?php
            //close to header

            //--------------------------PAGINATION-----------------------

            if(isset($_GET['page'])){
                $page_number = $_GET['page'];
            }
            else{
                //if for some reason page number not set
                $page_number=""; //empty string
            }

            if($page_number == "" || $page_number == 1){
                //if page is empty or page=1
                $num_of_posts_displayed = 0; //then we are on home page (Display posts from 0 to num_of_posts_per_page) (0-4)
            }
            else{
                //not on page 1

                //do some calculation 
                $num_of_posts_displayed = ($page_number * $num_of_posts_per_page) - $num_of_posts_per_page; //(Display posts from 0 to num_of_posts_per_page) (4-4)

                //e.g. on page=2, 2*4 = 8, 8-4=4 
                //therefore, 4 posts on page 2 ..and so on

            }
            
            //----------First come here, then to pager.php, then to above, and finally set the limit down in display posts----------

            //find out how many posts we have
            $sql = "SELECT * FROM posts";
            $result = mysqli_query($conn,$sql);
            $total_posts = mysqli_num_rows($result); 

            //LIMIT: - 4 posts per page (fixed above)
            $total_posts_per_page = ceil($total_posts/$num_of_posts_per_page); //need an integer - round it up => to get how many posts per page
            


            //--------------------------DISPLAYING POSTS---------------------------

            //HERE => we will use a special NEW TECHNIQUE - to display dynamic data

            //limit posts from  
            $sql="SELECT * FROM posts WHERE post_status='published' LIMIT {$num_of_posts_displayed},{$num_of_posts_per_page}";
            $result = mysqli_query($conn,$sql);

            while($row = mysqli_fetch_assoc($result)){
                $post_id = $row['post_id'];
                $post_title = $row['post_title'];
                $post_author = $row['post_author'];
                $post_date = $row['post_date'];
                $post_image = $row['post_image'];

                //excerpt the content on home page ((dont display all))= excerpt means shrink the content -> by substr()
                $post_content = substr($row['post_content'],0,250);
                //minimize from 0 to 250 characters
                

                //loop to keep picking up data from database

                //we do the following steps to display dynamic data
                //turn off php - (turn off loop)
            ?> 

            <!-- DISPLAYING DYNAMIC DATA -->

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <!-- Clicking on heading will re-dirtect to post page (pass post id to url - will be caught by GET in post page)-->
                <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
            </h2>

            <!-- Clicking on author will re-dirtect to page that will display all the authors posts-->
            <p class="lead">
                by <a href="post_author.php?p_author=<?php echo $post_author; ?>"><?php echo $post_author ?></a>
            </p>

            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
            <hr>
            <!-- create reference from database to image  -->
            <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
            <hr>
            <p><?php echo htmlspecialchars_decode($post_content); ?></p>

            <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

            <hr>
            <hr>

            <?php
                //turn php on again - (turn loop on again)
            }
            ?>

            <!-- Pager -->
            <?php
            //-------PAGINATION CONTINUE--------------
            include "includes/pager.php";
            ?>

        </div>

        <?php
        //error messages
        if (isset($_GET['error'])) {
            if ($_GET['error'] == 'emptyfields') {
                echo "<p style='color:red;font-size:25px'>Please Fill in all the Fields!<p>";
            }
            elseif ($_GET['error'] == 'sql') {
                echo "<p style='color:red;font-size:25px'>Error in SQL!<p>";
            }
            elseif ($_GET['error'] == 'username') {
                echo "<p style='color:red;font-size:25px'>Username does not exist!<p>";
            }
            elseif ($_GET['error'] == 'password') {
                echo "<p style='color:red;font-size:25px'>Incorrect Password!<p>";
            }
            elseif ($_GET['error'] == 'notallowed') {
                echo "<p style='color:red;font-size:25px'>Cannot be accessed withoout logging in!<p>";
            }
        }
        ?>


        <?php
        //success messages
        if (isset($_GET['success'])) {
            if ($_GET['success'] == 'logout') {
                echo "<p style='color:green;font-size:30px;text-align:center'>You are logged out!<p>";
            }   
        }
        ?>

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
    ?>
