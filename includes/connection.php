<?php 
/*
 * dbConnect.php created on Nov 19, 2006
 *
 * Tom Crosman
 */
 /**
 * Connects to the database.
 * Return false if connection failed.
 * Be sure to change the $database_name. $database_username , and 
 * $database_password values  to reflect your database settings.
 */

// Comment/uncomment according to debugging need...
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

function db_connect() {
    
    // (3/23/16, RC) Changed to mysql1 to avoid deprecated mysql functions
           
    $database_name = 'cmc_instructors'; // Set this to your Database Name
    $database_username = 'cmc_bio_admin'; // Set this to your MySQL username
    $database_password = 'b0uld3r'; // Set this to your MySQL password
    //$con = mysql_pconnect('mysql.cmcboulder.org', $database_username, $database_password) or
    // example: mysqli::real_connect ([ string $host [, string $username [, string $passwd [, string $dbname
    
    $con = mysqli_connect('mysql.cmcboulder.org',  $database_username, $database_password, $database_name );

   	if (mysqli_connect_errno()) {
        echo "Failed to connect to the ".$database_name." database: " . mysqli_connect_error();
        exit();
    }

    return $con;   
}
?>
