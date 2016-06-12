<?php
    session_start();
    $debug = 0;
    if ($debug) {
        echo "into see_class_history...printing all variables:<br>";
        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
    }
    define('_root',$_SERVER['DOCUMENT_ROOT']);
    include (_root.'/instructorDB_public/includes/connection.php');

    // Turn this on/off to control debugging in the controller. 
    $_SESSION["debug"] = 0;
    // Get the idInstructor from the SESSION variable:
        $dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());
        
    // The Instructor information is already available in SESSION varaibles; retrieve the class history for this instructor to display below.
    // If there are no results to display, show a message instead.
    // variable order: 1=class_name 2=season 3=year 4=idIHC (key field for instructor_has_classes table), 5=instructor_role
    
    $query = "SELECT SCH.school_name, C.class_name, S.season, IHC.year, IR.role_name, IHC.idIHC
    FROM cmc_schools SCH, cmc_classes C, instructors_have_classes IHC, seasons S, instructor_roles IR
    WHERE IHC.idInstructors=".$_SESSION["ID"]." 
    AND IHC.idSchools=SCH.idSchools
    AND IHC.idClasses=C.idClasses
    AND IHC.idSeasons=S.idSeasons
    AND IHC.idInstructor_role=IR.idInstructor_role
    ORDER BY year DESC";
    
    $result = mysqli_query($dbh,$query);
    
    if ($result) {
      $num_rows = mysqli_num_rows($result);
      
      $NO_DATA = False;
      if ($num_rows == 0) {
        $NO_DATA = True;
      } else {
        // There are classes for this instructor.
        // Reuse the same query by resetting the internal result pointer...
        if (!mysqli_data_seek($result, 0)) {
            echo "The mysql_data_see failed attempting to retrieve the class history. Please seek technical assistance.";
            exit;
        }
        // .... which sets up the query result to display below.
      }
      
      
    } else {
      echo "The query failed; here is the query:".$query;
    }    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php $page_title = "Instructor Class History"; ?>
    <?php include($_SERVER['DOCUMENT_ROOT'].'/includes/html_head.php'); ?>
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    
    <style type="text/css">
        /* from css3button.net/154343:  */
        #buttonstyle {
            border: 2px solid #777777;
            background: #019966;
            color: black;
            font: bold 20px 'Trebuchet MS';
            padding: 4px;
            cursor: pointer;
        }

        #buttonstyle_disabled {
            border: 2px solid #777777;
            background: #C3D6D0;
            color: grey;
            font: bold 20px 'Trebuchet MS';
            padding: 4px;
            cursor: pointer;
        }
        
        /* Use this technique to pass the key value for idIHC when a delete link is clicked: */
        td.hidden {display: none;}
        
    </style>

</head>
<body onload=chk_message() >
    
  <?php
    $header_image = "423-427-web.jpg";
    include('body_header.php');
  ?>
    <div id='content'>
    <form name="ClassHistoryForm"  method="post" enctype="multipart/form-data" action="instructorDB_controller.php" >
        
    <!--Note: this table should have 7 elements <td></td>, or equivalent colspan, per row.
        Tip: to modify the table structure, it helps to make borders visible (set border=1)
    -->
    <table style="width:100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td align="center" valign="top" colspan="6"><p><span class="head">Colorado Mountain Club - Boulder Group Instructor Database<br>
            Instructor Class History Form<br>
            for: <?php echo $_SESSION["fname"]." ".$_SESSION["lname"] ?>
            </span></p>
            </td>
        </tr>
    </table>
    <table style="width:60%" cellspacing="0" cellpadding="1" border="1" align="center">
        <?php
        
            if ($NO_DATA) {
                echo "<tr><td colspan=7 align=\"center\">There is no class history for this instructor.</td></tr>";
            } else {
                // print the column headers for columns 1-5: School, Class, Season, Year, Role
                echo "<tr><td align='center'>School</td><td align='center'>Class</td><td align='center'>Season</td><td align='center'>Year</td><td align='center'>Role</td><td></td><td></td></tr>";
                // field order: C.class_name, S.season, IHC.year, IHC.id, IR.role_name
                while($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
                  $school = $row[0];
                  $class = $row[1];
                  $season = $row[2];
                  $year = $row[3];
                  $role = $row[4];
                  $idIHC = $row[5];
                  echo "<tr>
                    <td>".$school."</td>
                    <td>".$class."</td>
                    <td>".$season."</td>
                    <td>".$year."</td>
                    <td>".$role."</td>
                    <td><a class='edit' align='center'>edit</a></td>
                    <td><a class='delete' align='center' >delete</a></td>
                    <td class='hidden'>$idIHC</td>
                  </tr>";
                }
            }          
        ?>
    </table>
    <table style="width:100%" cellspacing="0" cellpadding="0" border="0" align="center">
        <tr>
            <td colspan="4" align="center"><input  id="buttonstyle" type="submit" name="AddClass" value="Add class" /></td>
        </tr>

        <tr>
            <td></td>
            <td align="center" valign="bottom" ><p><a href="instructor_form.php" target="_self">Return to Instructor Edit</a></td>
            <td align="center" valign="bottom" ><p><a href="instructorDB_top.php" target="_self">Return to top</a></td>
            <td align="center" valign="bottom" ><p><a href="instructor_list.php" target="_self">Return to Instructor List</a></td>
            <td></td>
        </tr>
    </table>
    </form>
    </div>
    <!--    This javascript needs to be at the bottom of the page to ensure it runs correctly,
            i.e., after the entire page is loaded.
    -->
    <script type="text/javascript" >

        <?php
            // Display confirmation message when data is saved (via Submit button)
            if (isset($_GET['message']) && $_GET['message']== "delete_success") {
                echo "var message = 'Class deleted';";
            }
        ?>

        function chk_message() {
             if (!(typeof(message) === 'undefined')  )  {
                alert(message);
             }
        }        
        
        $(document).ready(function() {
    
            $('a.delete').click(function() {
    
                 // confirm the delete action  
                
                $('td').click(function() {
                    var $tr = $(this).closest('tr');
                    var row = $tr.index();
                    // Get the hidden input value, which contains key field, instructors_have_classes.idihc
                    var id = $(this).siblings(":hidden").html();

                    // for debugging:
                    //alert('You clicked Row: ' + row + ', with id: ' + id );
                    
                    if (confirm('Really delete this class?')) {
                        var url = "instructorDB_controller.php?ihcid=" + id + "&action=delete";
                        $(location).attr('href',url);
                    }
                });
            }  );
            
    
            $('a.edit').click(function() {
                
                $('td').click(function() {
                    var $tr = $(this).closest('tr');
                    var row = $tr.index();
                    // Get the hidden input value, which contains key field, instructors_have_classes.idihc
                    var id = $(this).siblings(":hidden").html();
    
                    // for debugging:
                    //alert('You clicked Row: ' + row + ', with id: ' + id );
                    
                    var url = "instructorDB_controller.php?ihcid=" + id + "&action=edit";
                    $(location).attr('href',url);
                });
            }  );
            
        });                 
    </script>
    
    <? include('../../includes/body_footer.php'); ?>

</body>
</html>