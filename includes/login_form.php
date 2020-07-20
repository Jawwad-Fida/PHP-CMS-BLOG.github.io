<!----------Login Form------------>

<div class="well">
    <h4 style='text-align:center;font-size:20px'>Login</h4>

    <form action="includes/login.php" method="post" autocomplete="off">

        <div class="form-group">

            <input name="username" type="text" class="form-control" placeholder="Enter your username">

            <input name="password" type="password" class="form-control" placeholder="Enter your password">

            <button type="submit" name="login_submit" class="form-control btn btn-primary">LOG IN</button>

        </div>

        <div class="form-group">
            <!-- Pass a random link - makes it look better-->
            <a class="btn btn-danger" href="forgot.php?forgot=<?php echo uniqid(true); ?>">Forgot Password</a>
        </div>

    </form>
</div>