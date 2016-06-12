<?
 // This is the starting form in the Instructor DB.
 
 session_start();

 // Define the GLOBALS array for variables used in Instructor Database app:
    
    $_SESSION["ID"] = 0;
    $_SESSION["fname"] = "";
    $_SESSION["lname"] = "";
    $_SESSION["email"] = "";
    $_SESSION["phone1"] = "";
    $_SESSION["phone2"] = "";
    $_SESSION["comment"] = "";
    $_SESSION["wfa_certified"] = 0;
    $_SESSION["wfa_expire_date"] = "";
    $_SESSION["photo"] = "";
    $_SESSION["photo_caption"] = "";
    $_SESSION["CMCmemberID"] = "";
    $_SESSION["active"] = 0;
    $_SESSION["show_profile"] = 0;
    $_SESSION["is_new"] = 0;
    $_SESSION["idGroups"] = 0;
    $_SESSION["crud_action"] = "";
    
    // Set the default group to be Boulder. This can be enhanced in the future to allow selection of which CMC Group to work on.
    
    define('_root',$_SERVER['DOCUMENT_ROOT']);
    include (_root.'/instructorDB_public/includes/connection.php');
    $dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());
    $query = "SELECT idGroups FROM cmc_groups WHERE cmc_group='Boulder'";
    $result = mysqli_query($dbh,$query);
    if ($result) {
        $row = mysqli_fetch_row($result);
        $_SESSION["idGroups"] = $row[0];
    } else {
        $rtnval = false;
    }
?>
<? include($_SERVER['DOCUMENT_ROOT'].'/includes/media_init.php'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <? $page_title = "Instructor Admin"; ?>
    <? include('html_head.php'); ?>
</head>
<body> 
<?php
  $header_image = "423-427-web.jpg";
  include('../../includes/body_header.php');
?>

<div id='content'>
<form name="top_form" action="instructorDB_controller.php" method="post">
<table width="850" cellspacing="20" cellpadding="0" border="0">
    <tr>
        <td align="center" valign="top">
            <p><span class="head">Colorado Mountain Club - Boulder Group<br />
            CMC Instructor Database Administration</span></p>
        </td>
    </tr>
    <tr><td align=center>
        <table cellspacing="0" cellpadding="10" border="0">                   
            <tr><td align="left"><p><span class="notice">What would you like to do?</span></p></td></tr>
            <tr><td>
                <input type="radio" name="top_select" value="add" />&nbsp;&nbsp;Add a New Instructor<br>
                <input type="radio" name="top_select" value="edit" />&nbsp;&nbsp;Edit Existing Instructor<br>
                <input type="radio" name="top_select" value="list" />&nbsp;&nbsp;See Instructor List<br>
                <input type="radio" name="top_select" value="admin" />&nbsp;&nbsp;School Directors Admin page<br>
                <input type="radio" name="top_select" value="adminexportform" />&nbsp;&nbsp;School Directors Export page
            </td></tr>                  
            <tr><td align="center"><input type="submit" name="submitTop" value="Submit" /></td></tr>
        </table>
</table>
</form>
</div>
<?php include('../../includes/body_footer.php');
  // debugging...
  //echo "pwd = ".getcwd() . "\n";
?>
</body>
</html>
