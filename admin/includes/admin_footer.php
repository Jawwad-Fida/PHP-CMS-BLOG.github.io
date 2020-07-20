</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- place the scripts to load them after the documents have been loaded (faster)-->
<script src="js/scripts.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>

<?php
ob_end_flush(); //for headers - stop buffering of webpage
mysqli_close($conn);
?>
