<!-- Footer -->
<footer>
    <div class="row">
        <div class="col-lg-12">
            <p style='text-align:center'> &copy; Jawwadul Islam Fida's Blog, <?php echo date("Y"); //dynamic date ?></p>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</footer>

</div>
<!-- /.container -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php
ob_end_flush();
mysqli_close($conn);
?>