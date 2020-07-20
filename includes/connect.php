<?php

//Rather than using variables, use arrays for faster programming (Good practice)

//Associative arrays -> arr_name['key'] = 'value'
$db['db_host'] = "localhost";
$db['db_user'] = "root";
$db['db_password'] = "";
$db['db_name'] = "cms";

//loop through the array, and convert all data in arrays into constants
foreach($db as $key => $value){
    //function to define constants
    define($key,$value);
}


$conn = mysqli_connect(db_host,db_user,db_password,db_name);

/*
if(!$conn){
    die("Connection Failed : " .mysqli_connect_error());
}
else{
    echo "Connection established successfully";
}
*/

