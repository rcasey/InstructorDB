<?
    session_start();
    include($_SERVER['DOCUMENT_ROOT'].'/includes/media_init.php'); 
    define('_root',$_SERVER['DOCUMENT_ROOT']);
    include (_root.'/instructorDB_public/includes/connection.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <? include('html_head.php'); ?>
    <? $page_title = "Instructor"; ?>
    <style type="text/css">
        .head {font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 16px;
            font-weight: bold;
            color: #003300;}
        .smallhead {font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            color: #003300;
            font-style: italic;}
        .tblHead {font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: bold;
            color: #003300;}
        .notice {color:#CC0033;
          font-weight:normal}
    </style>
</head>
<body >
  <? $header_image = "423-427-web.jpg"; ?>
  <? include('body_header.php'); ?>
<div id='content'>
<table width="850" cellspacing="10" cellpadding="0" border="0">
    <tr><td height="10" align="center" valign="top"><p><span class="head">Instructors for Boulder Mountaineering Schools (BMS)</span></p></td></tr>
    <tr><td align="center"><span class="smallhead">- alphabetical by first name -</span></td></tr>
    <tr><td height="*" align="center" valign="middle">
      <table cellspacing="10" cellpadding="3" border='0'>
      <tr><td align="center"><span class="head">Instructors</span></td></tr>
    <?php 
    
        $dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());
        
        // get table information
        // (This is where the CMC instructors could be displayed by schools could displayed, by modifying the SQL.)
        $query1 = "SELECT idInstructors AS ID, LName, FName FROM Instructors order by FName, LName ";
        $result1 = mysqli_query($dbh, $query1);
        if ($result1) {
          while($row = mysqli_fetch_array($result1,MYSQLI_NUM)) {
            $ID = $row[0];
            $lname = $row[1];
            $fname = $row[2];
            echo "<tr><td align=\"left\"><a href='instructorDB_controller.php?ID=$ID'>$fname $lname</a></td></tr>";
          } 
        } else {
          echo "Your database connection failed. Please seek technical assistance.";
        }
    ?>
      </table>
    </td></tr>
    <tr>
        <td align="center" valign="bottom"><p><a href="instructorDB_top.php" target="_self">Return to top</a></td>
    </tr>
</table>
</div>
<div id='content'>
<font size="-1"><i>
</i></font>
</div>
  <? include('../../includes/body_footer.php'); ?>
</body>
</html>

