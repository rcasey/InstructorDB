<?
    session_start();
    $debug = 0;
    if ($debug) {
        echo "into instructor_form...printing all variables:<br>";
        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
    }
    // Turn this on/off to control debugging in the controller. 
    $_SESSION["debug"] = 0;
    
    if (isset($message)) {
        if ($message == "saved") { $message = "Data saved."; }
    } else {
        unset($message);
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <? $page_title = "Instructor Edit Form"; ?>
    <? include($_SERVER['DOCUMENT_ROOT'].'/includes/html_head.php'); ?>
    
    
    <style type="text/css">
        /* from css3button.net/154343:  */
        #buttonstyle {
            border: 2px solid #777777;
            background: #019966;
            color: black;
            font: bold 18px 'Trebuchet MS';
            padding: 4px;
            cursor: pointer;
        }
        
        .heading{
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size:16px;
            font-weight:bold;
            text-align: center;
            color: #006633;
        }
        
        input {
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size:10px;
            font-weight:normal;
        }
        
        input[type=text] {
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size:12px;
            font-weight:normal;
        }
        
        input[type=date] {
            font-family:Verdana, Arial, Helvetica, sans-serif;
            font-size:12px;
            font-weight:normal;
        }
        
        td.class > div { width: 100%; height: 100%; overflow:hidden; height: 20px; }
        
    </style>


</head>
<body onload=chk_message() >
  <?php
    $header_image = "423-427-web.jpg";
    include('body_header.php');
  ?>
    <form name="InstructorForm" action="../instructorDB_protected/instructorDB_controller.php" method="post" enctype="multipart/form-data">
        
        <!-- Note: attempt to use just css instead of a table to format the input form. -->
        <div id='content'>
            <p><center>
                <span class="heading">Colorado Mountain Club - Boulder Group Instructor Database<br>Instructor Administrator Edit Form</span></p>
                School Qualifications for instructor: <?php { echo $_SESSION["fname"]." ".$_SESSION["lname"]; } ?>
                <p>
                Avalanche :<input  type="checkbox" name="avi_instructor" <?php { if ($_SESSION["avi_instructor"]) {echo "checked"; } } ?> /><br>
                Hiking :<input  type="checkbox" name="hiking_instructor" <?php { if ($_SESSION["hiking_instructor"]) {echo "checked"; } } ?> /><br>
                Ice :<input  type="checkbox" name="ice_instructor" <?php { if ($_SESSION["ice_instructor"]) {echo "checked"; } } ?> /><br>
                Rock, Trad :<input  type="checkbox" name="rock_instructor_trad" <?php { if ($_SESSION["rock_instructor_trad"]) {echo "checked"; } } ?> /><br>
                Rock, Sport :<input  type="checkbox" name="rock_instructor_sport" <?php { if ($_SESSION["rock_instructor_sport"]) {echo "checked"; } } ?> /><br>
                Ski :<input  type="checkbox" name="ski_instructor" <?php { if ($_SESSION["ski_instructor"]) {echo "checked"; } } ?> /><br>
                Snow :<input  type="checkbox" name="snow_instructor" <?php { if ($_SESSION["snow_instructor"]) {echo "checked"; } } ?> /><br>
                <input id="buttonstyle" type="submit" name="submitInstructorAdmin" value="Save" />
            </center>
            <br>
            <div class="left_align"><a href="../instructorDB_protected/instructorDB_top.php" target="_self">Return to top</a></div>
            <div class="right_align"><a href="instructor_admin_list.php" target="_self">Return to Instructor Admin List</a></div>
        </div>
        <? include('../../includes/body_footer.php'); ?>
    </form>
    
    <!--    This javascript needs to be at the bottom of the page to ensure it runs correctly,
            i.e., after the entire page is loaded.
    -->
    <script type="text/javascript" language="JavaScript">
    
        // Display confirmation message when data is saved (via Submit button)
        <?php
            if (isset($_GET['message']) && $_GET['message']== "saved") {
                echo "var message = 'Data saved';";
            }
        ?>
        
        function chk_message() {
             if (!(typeof(message) === 'undefined')  )  {
                alert(message);
             }
        }
        
        
    </script>

</body>
</html>