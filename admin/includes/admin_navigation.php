<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="index.php">CMS Admin</a> <!-- Click to go to Admin Page--> 

    </div>


    <!-- Top Menu Items -->
    <ul class="nav navbar-right top-nav">

        <!-- Users online (using ajax) -->
        <li><a href="">Users Online: <span class="usersonline"></span></a></li>

        <!-- Users online 
        <li><a href="">Users Online: <?php //echo users_online() ?></a></li> -->

        <li><a href="../index.php">Home Page</a></li> <!-- New Addition --> <!-- Click to go back to Home page-->


        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>

                <?php 

                $user_name = $_SESSION['user_name'];  
                echo $user_name;
                ?>

                <b class="caret"></b>
            </a>

            <ul class="dropdown-menu">

                <li>
                    <a href="profile.php"><i class="fa fa-fw fa-user"></i>Profile</a>
                </li>
                <li class="divider"></li>
                <li>
                    <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i>Log Out</a>
                </li>

            </ul>
        </li>

    </ul>


    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            

            <!-- PHP SHORT HAND IF STATEMENTS -->
            <!-- Dashboard will not be seen by Subscribers -->
            <?php if(is_admin()): ?>

            <li>
                <a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i>Dashboard</a>
            </li>

            <?php endif; ?>
            <!-- PHP SHORT HAND IF STATEMENTS -->

           
            <li>
                <a href="user_dashboard.php"><i class="fa fa-fw fa-dashboard"></i>My Data</a>
            </li>

            <!-- **data-target and id has to be same name**-->
            <li>
                <a href="javascript:;" data-toggle="collapse" data-target="#posts_dropdown"><i class="fa fa-fw fa-arrows-v"></i> Posts <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="posts_dropdown" class="collapse">

                    <li>
                        <a href="posts.php">View All Posts</a>
                    </li>

                    <li>
                        <a href="posts.php?source=add_post">Add Posts</a>
                    </li>

                </ul>
            </li>

            <?php
       if(isset($_SESSION['role'])){
              if(is_admin($_SESSION['user_name'])){
              //is_admin() function in functions.php
              //if the user is an admin, then show this page
                 echo '
                    <li>
                        <a href="categories.php"><i class="fa fa-fw fa-wrench"></i>Categories Page</a>
                   </li>
                    ';
               }
            }
            ?>

            <li class="">
                <a href="comments.php"><i class="fa fa-fw fa-file"></i>Comments</a>
            </li>

            <?php
            //USERS CAN ONLY BE SEEN BY ADMIN 

            if(isset($_SESSION['role'])){
                if(is_admin($_SESSION['user_name'])){
                    //if the user is an admin, then show this page
                    echo '
                   <li>
                     <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Users <i class="fa fa-fw fa-caret-down"></i></a>
                       <ul id="demo" class="collapse">
                           <li>
                              <a href="users.php">View All Users</a>
                          </li>
                          <li>
                        <a href="users.php?source=add_user">Add Users</a>
                         </li>
                      </ul>
                 </li>
                    ';
                }
            }


            ?>


            <li>
                <a href="profile.php"><i class="fa fa-fw fa-dashboard"></i>Profile</a>
            </li>

        </ul>
    </div>

    <!-- /.navbar-collapse -->
</nav>