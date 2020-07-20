<?php
//we will make this page dynamic - i.e. it will change according to data

include "includes/connect.php";
include "includes/header.php";
include "admin/functions.php";

?>


<!-- Navigation -->
<?php
include "includes/navigation.php";
?>


<?php
//-------------- POST LIKES------------

if(isset($_POST['liked'])){

    //get the values from the post request we are sending from below
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    //STEPS

    //1 = SELECT POST

    $result = query("SELECT * FROM posts WHERE post_id={$post_id}");
    confirmQuery($result);

    $row = mysqli_fetch_assoc($result);
    $likes = $row['likes']; //get the likes

    //2 = UPDATE POSTS WITH LIKES

    $result = query("UPDATE posts SET likes={$likes}+1 WHERE post_id={$post_id}"); //update likes by 1 when it is cliked
    confirmQuery($result);

    //3 = CREATE LIKES FOR POST

    //know which user has liked which post 
    $result = query("INSERT INTO likes(user_id,post_id) VALUES($user_id,$post_id)");
    confirmQuery($result);

    $result = query("SELECT * FROM posts");
    confirmQuery($result);
    $row = mysqli_fetch_assoc($result);
    $dislikes = $row['dislikes'];

    //prevent dislikes from reaching a -ve value
    if($dislikes != 0){
        $result = query("UPDATE posts SET dislikes={$dislikes}-1 WHERE post_id={$post_id}"); //update dislikes by -1 when clicked
        confirmQuery($result);
    }

    exit(); //terminate process to finish it up

}


//----------------- POST DISLIKE--------------

if(isset($_POST['disliked'])){

    //get the values from the post request we are sending from below
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];

    //STEPS

    //1 = FETCH LIKES FROM THE CORRECT POST 

    $result = query("SELECT * FROM posts WHERE post_id={$post_id}");
    confirmQuery($result);

    $row = mysqli_fetch_assoc($result);
    $likes = $row['likes']; 
    $dislikes = $row['dislikes']; 

    //2 = DELETE LIKES FOR THAT POST 

    $result = query("DELETE FROM likes WHERE user_id={$user_id} AND post_id={$post_id}");
    confirmQuery($result);

    //3 = UPDATE DECREMENT LIKE and INCREMENT DISLIKE FOR THAT POST

    $result = query("UPDATE posts SET dislikes={$dislikes}+1 WHERE post_id={$post_id}");
    confirmQuery($result);

    //prevent likes from reaching a -ve value
    if($likes != 0){
        $result = query("UPDATE posts SET likes={$likes}-1 WHERE post_id={$post_id}");
        confirmQuery($result);
    }

    exit(); //terminate process to finish up dislike

}

?>


<!-- Page Content -->
<div class="container">
    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">

            <?php
            //catch the id from url

            if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id']; 

                //GET NUMBER OF VIEWS TO A POST
                //increment views each time a visitor visits a post

                $views_sql = query("UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id=$the_post_id");
                confirmQuery($views_sql);

                //QUERY TO DISPLAY POSTS BASED ON POST ID

                $result=query("SELECT * FROM posts WHERE post_id={$the_post_id}");
                confirmQuery($result);

                while($row = mysqli_fetch_assoc($result)){

                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = $row['post_content'];

                    //loop to keep picking up data from database

                    //turn off php - (turn off loop)
            ?> 

            <!-- DISPLAYING DYNAMIC DATA -->

            <h1 class="page-header">
                Page Heading
                <small>Secondary Text</small>
            </h1>

            <!-- First Blog Post -->
            <h2>
                <a href="#"><?php echo $post_title ?></a>
            </h2>
            <p class="lead">
                by <a href="post_author.php?p_author=<?php echo $post_author; ?>"><?php echo $post_author ?></a>
            </p>
            <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
            <hr>
            <!-- create reference from database to image  -->
            <img class="img-responsive" src="images/<?php echo $post_image ?>" alt="">
            <hr>
            <p><?php echo htmlspecialchars_decode($post_content); ?></p>

            <hr>
            <hr>

            

            <!---------------------------- DISPLAY LIKES and DISLIKES ----------------------->

            <?php
                    if(!isset($_SESSION['userID'])){
                        echo "<h3 style='text-align:center;color:brown'>Login to like or dislike</h3>";
                    }
                    else{
                        //close off php tag
            ?>

            <div class="row">

               <!-- Data toggle -->
               <!-- Placement -> top/bottom/right/left -->
                <p class="pull-left">
                    <a href="" id="like" class="btn btn-success btn-lg">
                        <span class="glyphicon glyphicon-thumbs-up" 
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Click here to Like this Post"></span> Like
                    </a>
                </p>

                <p class="pull-right">
                    <a href="" id="dislike" class="btn btn-danger btn-lg">
                        <span class="glyphicon glyphicon-thumbs-down"
                        data-toggle="tooltip"
                        data-placement="top"
                        title="Click here to Dislike this Post"></span> Dislike
                    </a>
                </p>
                
                <!-- now activate toggle with js -->

            </div>

            <br>

            <?php } //re-open php tag ?>

            <div class="row">
                <p class="pull-left btn btn-info">Likes: <?php echo get_likes($the_post_id); ?></p>
                <p class="pull-right btn btn-info">Dislikes: <?php echo get_dislikes($the_post_id); ?></p>
            </div>

            <br>

            <div class="clearfix"></div>

            <!-- --------------------------------------------------------------------------- -->

            

            <?php
                    //turn php on again - (turn loop on again)
                }
            }
            else{
                //in case if there is a false id
                header("Location: index.php");
            }

            ?>


            <!------------------------------ Posted Comments ------------------------------------------>

            <?php
            //catch the comment form here
            if(isset($_POST['create_comment'])){

                //get the post id first and post title (IMP)
                $the_post_id = $_GET['p_id'];

                $comment_user = validate($_POST['comment_user']);
                $comment_user = mysqli_real_escape_string($conn,$comment_user);

                $comment_email = validate($_POST['comment_email']);
                $comment_email = mysqli_real_escape_string($conn,$comment_email);

                $comment_content = validate($_POST['comment_content']);
                $comment_content = mysqli_real_escape_string($conn,$comment_content);

                $comment_status = 'Pending';
                $comment_date = date("d-m-Y");

                //Check for Errors
                if(empty($comment_content)) {
                    header("Location: post.php?error=empty&p_id=".$the_post_id);

                    //Using JS to diplay a message
                    //echo "<script>alert('Fields cannot be empty')</script>";
                    exit();
                } 


                $sql = "INSERT INTO comments(comment_post_id,comment_post_title,comment_user,comment_post_author,comment_email,comment_content,comment_status,comment_date) VALUES(?,?,?,?,?,?,?,?)";

                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt,$sql)){
                    die("Unable to comment - Failed " .mysqli_error($conn));
                }
                mysqli_stmt_bind_param($stmt,"isssssss",$the_post_id,$post_title,$comment_user,$post_author,$comment_email,$comment_content,$comment_status,$comment_date);

                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header("Location: post.php?success=insert&p_id=".$the_post_id);
            }
            ?>

            <?php
            //Error Messages
            if (isset($_GET['error'])) {
                if ($_GET['error'] == 'empty') {
                    echo "<p style='color:red;font-size:25px'>Please Fill in all the Fields!<p>";
                }
            }

            //success messages
            if (isset($_GET['success'])) {
                if ($_GET['success'] == 'insert') {
                    echo "<p style='color:green;font-size:25px'>Comment Submitted! Waiting for Approval!<p>";
                }
            }
            ?>

            <!----------------- Comment Form ---------------------->
            <?php
                    if(!isset($_SESSION['userID'])){
                        echo "<h3 style='text-align:center;color:blue'>Login to comment</h3>";
                    }
                    else{
                        //close off php tag
            ?>
            
            
            <div class="well">
                <h4>Leave a Commment: </h4>

                <!-- Send form data to same page => action=""-->

                <form role="form" action="" method="post">

                    <div class="form-group">
                        <label for="Author">User</label>
                        <input class="form-control" type="text" value="<?php echo get_username(); ?>" name="comment_user">
                    </div>

                    <div class="form-group">
                        <label for="Email">Email</label>
                        <input class="form-control" type="email" name="comment_email">
                    </div>

                    <div class="form-group">
                        <label for="comment">Your Comment</label>
                        <textarea class="form-control" rows="3" name="comment_content"></textarea>
                    </div>

                    <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>

                </form>
            </div>
            
            <?php } //re-open php tag ?>
            
            
            <hr>

            <?php
            //select * from comments table based on comment id and status
            //comments are arranged in descending order (recent comments are shown first)

            $sql = "SELECT * FROM comments WHERE comment_post_id={$the_post_id} AND comment_status='Approved' ORDER BY comment_id DESC";
            $result = mysqli_query($conn,$sql);

            if(!$result){
                die("Comment not shown - Failed " .mysqli_error($conn));
            }
            $rowNumber = mysqli_num_rows($result);

            while($row = mysqli_fetch_assoc($result)){
                $comment_date = $row['comment_date'];
                $comment_content = $row['comment_content'];
                $comment_user = $row['comment_user'];

                //close off php tag
            ?>

            <!-------------- DISPLAY Comments ---------------->
            <div class="media">

                <a class="pull-left" href="#">
                    <img class="media-object" src="http://placehold.it/64x64" alt="">
                </a>

                <div class="media-body">
                    <h4 class="media-heading"><?php echo $comment_user; ?>
                        <small><?php echo $comment_date; ?></small>
                    </h4>
                    <?php echo $comment_content; ?>
                </div>

            </div>

            <?php
            }

            //Update the comment count in posts table

            //This value changes dynamically as comments are approved and deleted

            $sql = "UPDATE posts SET post_comment_count={$rowNumber} WHERE post_id={$the_post_id}";
            $result = mysqli_query($conn,$sql);

            if(!$result){
                die("Comment not shown - Failed " .mysqli_error($conn));
            }
            ?>      

        </div>

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

    <!-------------------------------- LIKES AND DISLIKES using JQUERY and AJAX ------------------>
    <?php
    if(isset($_SESSION['userID'])){
        $the_user_id = $_SESSION['userID']; 
    }
    ?>

    <script>

        $(document).ready(function(){
            //jquery
            
            //target data-toggle
            $("[data-toggle='tooltip']").tooltip();
            
            //assign php variable to js variable
            var user_id = <?php echo $the_user_id; ?>; //dont forget to add an extra semicolon

            var post_id = <?php echo $the_post_id; ?>;

            //------- LIKE -------

            $('#like').click(function(){

                $.ajax({ 
                    //ajax

                    url:"post.php?p_id=<?php echo $the_post_id; ?>", //clicking on link will send data to page
                    type: 'post', 
                    data:{
                        'liked': 1, //just assign a value
                        'post_id': post_id,
                        'user_id': user_id
                    }

                });
            });

            //------- DISLIKE -------

            $('#dislike').click(function(){

                $.ajax({ 
                    //ajax

                    url:"post.php?p_id=<?php echo $the_post_id; ?>", //clicking on link will send data to page
                    type: 'post', 
                    data:{
                        'disliked': 1, //just assign a value
                        'post_id': post_id,
                        'user_id': user_id
                    }

                });
            });

        });

    </script>





