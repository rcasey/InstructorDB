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
        
/*        td.class > div { width: 100%; height: 100%; overflow:hidden; height: 20px; }
*/        
        #buttonstyle {
            border: 2px solid #777777;
            background: #019966;
            color: black;
            font: bold 14px 'Trebuchet MS';
            padding: 4px;
            cursor: pointer;
            width: 150px;
            border-radius: 12px;
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
        
        
        ul {
            list-style: none;
            text-align: left;
        }
        
 /*       input{
            width:20px;
        
            position:relative;
            left: 150px; 
        
            vertical-align:middle; 
        }*/
        
    </style>


</head>
<body onload="chk_message()" >
  <?php
    $header_image = "423-427-web.jpg";
    include('body_header.php');
  ?>
    <form name="InstructorForm" action="../instructorDB_protected/instructorDB_controller.php" method="post" enctype="multipart/form-data">
        <div id='content'>
            <center>
                <span class="heading">Colorado Mountain Club - Boulder Group Instructor Database<br>Instructor Administrator Export Form</span></p>
                <p>
                Select which schools to export to a file to download.<br>
                The file will contain a list of instructor names, emails and phone numbers:
                <p>
                <table>
                    <tr>
                        <td>                        
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" name="avi_instructor" > Avalanche
                        </td>    
                    </tr>
                    <tr>
                        <td><input  type="checkbox" name="hiking_instructor" > Hiking
                    <tr>
                        <td>
                            <input  type="checkbox" name="ice_instructor" > Ice
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input  type="checkbox" name="rock_instructor_trad" > Rock, Trad
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input  type="checkbox" name="rock_instructor_sport" > Rock, Sport
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input  type="checkbox" name="ski_instructor" > Ski
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input  type="checkbox" name="snow_instructor" > Snow
                        </td>    
                    </tr>
                    <tr>
                        <td>
                            <input  type="checkbox" name="all_instructors" > Export all instructors
                        </td>    
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <td>
                            <input id=buttonstyle  type="submit" name="submitAdminExport" value="Export">
                        </td>    
                    </tr>
                </table>
                <br>
                    
                <br>
                <a href="../instructorDB_protected/instructorDB_top.php" target="_self">Return to top</a>
            </center>
        </div>
    </form>
    <? include('../../includes/body_footer.php'); ?>
    
    <!--    This javascript needs to be at the bottom of the page to ensure it runs correctly,
            i.e., after the entire page is loaded.
    -->
    <script type="text/javascript" language="JavaScript">
    
        // Display confirmation message when data is saved (via Submit button)
        <?php
            if (isset($_GET['message']) && $_GET['message']== "saved") {
                echo "var message = 'Data saved';";
            } elseif (isset($_GET['message'])) {
                 echo "var message = '".$_GET['message']."';";
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