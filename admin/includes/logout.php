<?php

session_start();

//end the session
session_unset(); //remove the session variables
session_destroy(); //destroy

header("Location: ../../index.php?success=logout");
