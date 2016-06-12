<?php
    session_start();
    $debug = 0;
    if ($debug) {
        echo "into class_edit_form...printing all variables:<br>";
        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
    }
    // Turn this on/off to control debugging in the controller. 
    $_SESSION["debug"] = 0;
    
    define('_root',$_SERVER['DOCUMENT_ROOT']);
    include (_root.'/instructorDB_public/includes/connection.php');
    $dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());
    
    // The session variables are assigned values in the controller, either blanks
    // for a new class, or the values for the class picked in the Instructor Class History form. 
    
    /* Database queries required:
     *  - Schools picklist
     *  - Classes picklist
     *  - Season picklist
     *
     *  If input parameter is edit, then need position the selected picklists
     *  on the appropriate values. The id's for those are passed via session variables:
     *
     *  SQL to show Schools & Classes within schools:
     *  SELECT school_name, idSchools, class_name, idClasses FROM cmc_schools S, cmc_classes C WHERE C.idSchools=S.idSchools AND S.idGroups=1 ORDER BY school_name, class_name;
     */    
    
    // Detect which action was passed, which is either no action or an edit action:
    $IS_EDIT = FALSE;
    if ( isset($_GET['action']) && isset($_GET['ihcid']) ) {
        
        $IS_EDIT = TRUE;
        $action = $_GET['action'];
        $ihcid = $_GET['ihcid'];
        
        // Retrieve the data for this instructor_has_classes record (only need the foreign keys from the instructors_have_classes record to set the picklists):
        $query =  "SELECT idIHC, idSchools, idClasses, idSeasons, idInstructor_role, year FROM instructors_have_classes WHERE idIHC =".$ihcid.";"; 
    
        $ihc_result = mysqli_query($dbh,$query);
    
        if (!$ihc_result) {
            echo "The instructors_have_classes query failed: ".$query."<br>";
            die(mysqli_error());
        } else {
            // Assign data to variables
            $row = mysqli_fetch_array($ihc_result,MYSQLI_NUM);
            $idSchools_to_show = $row[1];
            $idClasses_to_show = $row[2];
            $idSeasons_to_show = $row[3];
            $idInstructor_role_to_show = $row[4];
            $year_to_show =  $row[5];
        }
        
        // debugging:
        //echo "The instructors_have_classes query to get data to edit: ".$query."<br>";
        //echo "The data:<br>";
        //print_r($row);
        //echo "At top of page: idSchools_to_show = $idSchools_to_show, 
        //    idClasses_to_show = $idClasses_to_show, 
        //    idSeasons_to_show = $idSeasons_to_show, 
        //    idInstructor_role_to_show = $idInstructor_role_to_show, 
        //    year_to_show = $year_to_show";
        
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <?php $page_title = "Class Edit Form"; ?>
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
    </style>

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
    
            
    <script type="text/javascript">    
        // Use JQuery to constuct the picklists that will change the classes based on the chosen school:
        /*
         * Note: these hand-coded select lists below need to be converted to code that generates them from the database.
         * 
         */
        $(document).ready(function() {
            
            //$("#school").change(function() {
            //    
            //    var val = $("#school option:selected").text();
            //    
            //    
            //    if (val == "Hiking") {
            //        $("#class").html("<option value=4>Backpacking</option><option value=1>Hiking and Survival</option><option value=2>Hiking Navigation</option><option value=3>Hiking Route and Trip Planning</option>");
            //    } else if (val == "Rock Climbing") {
            //        $("#class").html("<option value=21>BRS (pre 2016)</option><option value=6>Rock Leading</option><option value=20>Rock Seconding School</option><option value=10>Rock Self Rescue</option><option value=5>Single Pitch Climbing</option><option value=8>Sport Leading</option><option value=9>Top Rope Anchor Clinic</option>");
            //    } else if (val == "Snow") {
            //        $("#class").html("<option value=11>Basic Snow</option><option value=12>Intermediate Snow</option>");
            //    } else if (val == "Winter") {
            //        $("#class").html("<option value=18>Area Telemark</option><option value=14>Avalanche Clinics</option><option value=17>Backcountry Telemark</option><option value=>16Cross Country Skiing</option><option value=19>Ski Mountaineering</option><option value=13>Winter Camping</option><option value=7>Ice Climbing</option>");
            //    } else {
            //        alert("Error: no value selected for School?");
            //    }
            //} );
            
            // If this is an edit, set the picklists to retrieved values: 
            
        } );
        
    </script>
    
</head>
<body onload=chk_message() >
  <?php
    $DEBUG = FALSE;
    $header_image = "423-427-web.jpg";
    include('body_header.php');
    IF ($debig) {        
        echo "In middle of page: idSchools_to_show = $idSchools_to_show, 
        idClasses_to_show = $idClasses_to_show, 
        idSeasons_to_show = $idSeasons_to_show, 
        idInstructor_role_to_show = $idInstructor_role_to_show, 
        year_to_show = $year_to_show";
    }

  ?>
    <div id='content'>
    <form name="ClassEditForm" action="instructorDB_controller.php" method="post" enctype="multipart/form-data">
        
    <!--Note: this table should have 4 elements <td></td>, or equivalent colspan, per row.
        Tip: to modify the table structure, it helps to make borders visible (set border=1)
    -->
    <table style="width:100%" cellspacing="0" cellpadding="0" border="0"align="left">
        <tr>
            <td align="center" valign="top" colspan="4"><p><span class="head">Colorado Mountain Club - Boulder Group Instructor Database<br>Instructor-Class Edit Form</span></p></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><span class="head" >Name: <?php echo $_SESSION["fname"]." ".$_SESSION["lname"]; ?></span></td>
        </tr>
        <tr>
            <td>School:</td>
            <td>
                <select id=school name=school >
                    <?php
                        // The Schools picklist:
                        $query = "SELECT idSchools,school_name FROM cmc_schools WHERE idGroups = ".$_SESSION["idGroups"]." ORDER BY school_name ASC";
                        //echo "The Schools query: ".$query;
                        $schools_result = mysqli_query($dbh,$query);                        
                        if (!$schools_result) {
                            echo "The cmc_schools query failed. The query: ".$query."<br>";
                            die(mysqli_error());
                        } else {
                            if ($DEBUG) { echo "idSchool_to_show: $idSchools_to_show"; }
                            while ($row = mysqli_fetch_array($schools_result)) {
                              $idSchool = $row[0];
                              $school = $row[1];
                              if ($DEBUG) { echo "Schools:"; print_r($row); }
                              if ($IS_EDIT && $idSchool == $idSchools_to_show) {
                                  echo "<option value= $idSchool selected >".$school."</option>";                                                        
                              } else {
                                  echo "<option value= $idSchool>".$school."</option>";                            
                              }
                            }
                        }
                   ?>
                </select
            </td>
        </tr>
        <tr>
            <td>Class:</td>
            <td>
            <select id=class name=class>
                    <?php
                        // The Classes picklist:
                        $query = "SELECT idClasses,class_name
                        FROM cmc_classes
                        WHERE idGroups = ".$_SESSION["idGroups"]." 
                        ORDER BY class_name ASC";
                        
                        $classes_result = mysqli_query($dbh,$query);
                        
                        if (!$classes_result) {
                            echo "The cmc_classes query failed. Please seek technical assistance. Here is the query: ".$query."<br>";
                            exit;
                        } else {                   
                            if ($DEBUG) { echo "idClass_to_show: $idClasses_to_show"; }
                            while ($row = mysqli_fetch_array($classes_result,MYSQLI_NUM)) {
                              $idClass = $row[0];
                              $class = $row[1];
                              if ($DEBUG) {echo "Classes:"; print_r($row);}
                              if ($IS_EDIT && $idClass == $idClasses_to_show) {
                                  echo "<option value=$idClass selected >".$class."</option>";                                                        
                              } else {
                                  echo "<option value=$idClass>".$class."</option>";                            
                              }
                            }
                        }
                   ?>                
            </select>         
            </td>
        </tr>            
        <tr>
            <td>Season:</td>
            <td>
            <select id=season name=season>
                <?php
                    // The Seasons picklist:
                    $query = "SELECT idSeasons,season FROM seasons ORDER BY rec_order ASC";
                    
                    $seasons_result = mysqli_query($dbh,$query);
                    
                    if (!$seasons_result) {
                        echo "The seasons query failed. Please seek technical assistance. Here is the query: ".$query."<br>";
                        exit;
                    } else {
                        if ($DEBUG) { echo "idSeason_to_show: $idSeasons_to_show"; }
                        while($row = mysqli_fetch_array($seasons_result,MYSQLI_NUM)) {
                            $idSeason = $row[0];
                            $season = $row[1];
                            if ($DEBUG) {echo "Seasons:"; print_r($row);}
                            if ($IS_EDIT && $idSeason == $idSeasons_to_show) {
                               echo "<option value=$idSeason selected >".$season."</option>";                                                        
                            } else {
                               echo "<option value=$idSeason >".$season."</option>";
                            }
                        }
                    }
                ?>                
            </select>
            </td>
        </tr>            
        <tr>
            <td>Instructor Role:</td>
            <td>
            <select id=role name=role>
                <?php
                    // The Instructor_role picklist:
                    $query = "SELECT idInstructor_role,role_name FROM instructor_roles";
                    
                    $roles_result = mysqli_query($dbh,$query);
                    
                    if (!$roles_result) {
                        echo "The Instructor_role  query failed. Please seek technical assistance. Here is the query: ".$query."<br>";
                        exit;
                    } else {
                        if ($DEBUG) { echo "idInstructor_role_to_show: $idInstructor_role_to_show"; }
                        while($row = mysqli_fetch_array($roles_result,MYSQLI_NUM)) {
                            $idInstructor_role = $row[0];
                            $role = $row[1];
                            if ($DEBUG) {echo "Instructor_roles:"; print_r($row);}
                            if ($IS_EDIT && $idInstructor_role == $idInstructor_role_to_show) {
                                echo "<option value=$idInstructor_role selected >".$role."</option>";                                                        
                            } else {
                                echo "<option value=$idInstructor_role >".$role."</option>";
                            }
                        }
                    }
                ?>                
            </select>
            </td>
        </tr>
        <tr>
            <td>Year:</td>
            <td><input type=text name=year size=4 maxlength=4 <?php if ($IS_EDIT) { echo "value=".$year_to_show; } ?>   /></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><input  id="buttonstyle" type="submit" name="saveInstructorClass" value="Save" /></td>
        </tr>
        <tr>
            <td></td>
            <td align="center" valign="bottom" ><p><a href="see_class_history.php" target="_self">Return to Class History</a></td>
            <td align="center" valign="bottom" ><p><a href="instructorDB_top.php" target="_self">Return to top</a></td>
            <td align="center" valign="bottom" ><p><a href="instructor_list.php" target="_self">Return to Instructor List</a></td>
            <td></td>
        </tr>
    </table>
    </form>
    </div>
    
    <? include('../../includes/body_footer.php'); ?>

</body>
</html>