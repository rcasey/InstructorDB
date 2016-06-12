<?php

/* This script processes choices made from several forms used in the Instructor DB.
 *
 * This script is an attempt to implement an MVC (model-view-controller) structure to this application.
 * It gets called from all of the forms and menus (views) presented to in the UI (user interface).
 * However, many of the database calls (models) are also done in the views, so the MVC implementation is only partially done.
 *
 * GET variables:
 *
 * from instructorDB_top.php:
 * $_GET['see_public_list']
 *
 * from see_class_history.php:
 * $_GET['ihcid']
 * 
 *  POST variables that get set from instructorDB_top.php are:
 *  menu choice               $_POST['submitTop'] value:         action
 *  -----------               ---------------------------         ---------------
 *  Add                         add                               calls instructor_form.php w/ blank variables
 *  Edit                        edit                              calls get_member_id.php (which sets $_POST['submitMemberID'])
 *                                                                & then calls instructor_form.php
 *  See Instructor list         list                              calls instructor_list.php
 *  School Director Admin       admin                             calls ../instructorDB_admin/instructor_admin_list.php (password protected)
 *
 * POST variables that get set from see_class_history.php are:
 * 
 * Add          - $_POST['editClassHistory']='Add'
 * Edit         - $_POST['editClassHistory']='Edit'
 * Delete       - $_POST['editClassHistory']='Delete'
 *
 * $_POST['submitInstructor']    -- used to submit from instructor_form.php
 * $_POST['saveInstructorClass'] -- used to submit from class_edit_form.php 
 *
 */

session_start();

//echo "pwd = ".getcwd() . "\n";
define('_root',$_SERVER['DOCUMENT_ROOT']);
include (_root.'/instructorDB_public/includes/connection.php');

// Use this for any queries below: 
$dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());

// Get the calling file:
//$calling_file = debug_backtrace()[1]['function'];
// debug
//echo "calling file is: ".$calling_file;

// debugging
if (0) {
    echo "into instructorDB_controller ...printing all vars:<br>";
    echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
    exit;
}

/*************************************************
 *    (NOT YET IMPLEMENTED 4//6/16)
 *    origin: tbd
 *    cause: 
 *    action: 
**************************************************/
if ( isset($_GET['see_public_list']) ) {       // the Instructor Bios choice was selected public menu 
    //
    //// show the list of public Instructor Bios, based on GET variable.
    //// (In the future, there may be a private list, for instructors only)
    //
    //if  ($_GET['see_public_list']=='yes') {
    //    header('Location: /instructorDB/instructor_list.php;view=public');
    //} else {
    //    header('Location: /instructorDB/instructor_list.php;view=private');        
    //}

/*************************************************
 *    origin:
 *    cause: 
 *    action: 
**************************************************/
} elseif (isset($_POST['submitTop'])) { // the instructorDB_top form was submitted
    
  // see what option was selected; top_select is the main radio button in the instructorDB_top form:
    
    if (isset($_POST['top_select'])) {
    
        // determine what radio button within top_select was selected:
        $selection = $_POST['top_select'];
        // act on selected option
        if ($selection == 'add') {
            get_instructor_variables("","");       // creates empty variables
            $_SESSION["is_new"] = 1;    // set flag that this is a new instructor
            header('Location: instructor_form.php');             
        } elseif ($selection == 'edit') {             
          // get the CMC member ID to edit
          header('Location: get_member_id.php');
        } elseif ($selection == 'list')  {
          // show the list of instructors
          header('Location: instructor_list.php?see_public_list=no');
        } elseif ($selection == 'admin')  {
          // show the School Director Admin page
          header('Location: ../instructorDB_admin/instructor_admin_list.php');
        } elseif ($selection == 'adminexportform')  {
          // show the Admin Export page
             header('Location: ../instructorDB_admin/instructor_admin_export_form.php');
        } else {
            echo 'Unknown selection submitted from Top form...aborting';
            quit;
        }
    }

/*************************************************    
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_POST['submitMemberID'])) { // the get_member_id form was submitted
    
    // assign the key field CMCMeberID field to a session variable before calling next form;
    // otherwise, the POST variable will be lost.
    if (isset($_POST['CMCMemberID']))  {  
        $CMCMemberID = $_POST['CMCMemberID'];
        if (is_valid_cmcmemberid($CMCMemberID)) {
            get_instructor_variables($CMCMemberID,"TRUE");
            header('Location: instructor_form.php');              
        } else {
            echo "Error! CMCMemeberID ".$CMCMemberID." not found.";
        }
    }    

/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_POST['submitInstructor'])) {   // the instructor_form was submitted to be saved
    
    /* 
     * Save the variables, 
     * if any error saving, print an error message. 
     */
    
    if (!save_instructor_variables()) {
        echo "Error! Instructor variables not saved around line 140.";
    }
    
    // reassign to session variables, so any changes get shown:
    
    // for debugging....set $debug=1
    $debug = 0;
    if ($debug) {
        echo "Data saved....about to return to Instructor Form....CMCMemberID = ".$_SESSION["CMCmemberID"]."<hr>";
        var_dump($_SESSION);
        exit;
    }
    
    // Retrieve variables based on whether this was a new instructor or not.
    
    if ($_SESSION["is_new"]) {
        // When saving a new instructor, they will not yet have a database-assigned ID,
        // so use the CMCmemberID that was supplied in the form.
        get_instructor_variables($_SESSION['CMCmemberID'],"TRUE");            
    } else {
        // Here we are saving an existing instructor; use the database-assigned ID,
        // and set the second parameter, VIA_MEMBERID, to false.
        get_instructor_variables($_SESSION['ID'],"FALSE");            
    }

    // Before returning, turn off the is_new flag. 
    $_SESSION["is_new"] = 0;

    // Return to the instructor_form, with a message.
    header('Location: instructor_form.php?message=saved');
    
/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_POST['seeClassHistory'])) {   // the instructor_form was submitted by clicking the See Class History button
    
    //echo "the instructor_form was submitted by pushing the See Class History button for idInstructors=".$_SESSION['ID']."...";
    //exit;
    // add $_SESSION['ID'] ....

    header('Location: see_class_history.php');
    
/*************************************************    
*    origin:
*    cause: 
*    action:
***************************************************/
} elseif (isset($_POST['AddClass'])) { // the Add Class button in See Class History form was submitted
            
    //echo "hit the AddClass branch...<br>";
    //quit;
    
    // Send to class_edit_form
    header('Location: class_edit_form.php');

    
/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_POST['saveInstructorClass'])) { // the Class Edit form was submitted to save a class for an instructor
    

    $idInstructors = $_SESSION["ID"];
    $idSchools = $_POST["school"];
    $idClasses = $_POST["class"];
    $idSeasons = $_POST["season"];
    $idInstructor_role = $_POST["role"];
    $year = $_POST["year"];
    
    // The SQL to save the record in instructors_have_classes:
    $sql = "INSERT INTO instructors_have_classes (idInstructors, idSchools, idClasses, idSeasons, idInstructor_role, year)
    VALUES (".$idInstructors.", ".$idSchools.", ".$idClasses.", ".$idSeasons.", ".$idInstructor_role.", ".$year.");";
    

    $result = mysqli_query($dbh,$sql);
    if (!$result) {
        echo "Error: query to save record in inststructors_have_classes failed. Here is the query: ".$sql;
    } else {
        // Return to class_edit_form and display confirmation message:
        header('Location: class_edit_form.php?message=saved');
    }
    

/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_GET['ihcid'])) { // the edit or delete button in the See Class History form was submitted
    
    
    // Determine which action to take
    $action = $_GET['action'];
    $ihcid = $_GET['ihcid'];
    
    //echo "Into the instructor_has_classes section, with action = ".$action.",a nd ihcid =  ".$ihcid."<br>";
        
    if ($action == 'delete') {
        
        // for debugging:
        //echo "About to delete the class in database with idIHC=".$ihcid."....<br>";
        
        // Delete the indicated record in instructor_has_classes
        $del_query = "DELETE FROM instructors_have_classes WHERE idIHC=".$ihcid.";";
        
        //echo "Here's the delete query: ".$del_query;
        
        $result = mysqli_query($dbh,$del_query);
        if (!$result) {
            echo "Error: The attempt to delete a class in the instructors_have_classes table failed. Here is the query: <br>".$del_query."<br>";
            die('Here is the error message: ' . mysqli_error());
        } else {
            // Return to see_class_history with message
            header('Location: see_class_history.php?message=delete_success');
        }
        
    } elseif ($action == 'edit') {
        
        //echo "Reached the edit branch...to call the class_edit_form....";
        //quit;
        header('Location: class_edit_form.php?action=edit&ihcid='.$ihcid);
        
    } else {
        echo 'Unknown action sent from See Class History; aborting...';
        quit;
    }
    

/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_GET['ID']))  {
    
    // debugging
    //echo "Into GET ID branch...<br>";
    if (0) {
        echo "into instructor_list branch of controller...printing all vars:<br>";
        echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>';
        exit;
    }

    // an instructor_list was submitted with an ID
    // retrieve the data for the instructor indicated in the GET variable

    $ID = $_GET["ID"];
    
    //$VIA_MEMBERID is second parameter:
    get_instructor_variables($ID,"FALSE");
    header('Location: instructor_form.php');
    
/*************************************************
*    origin:
*    cause: 
*    action:
**************************************************/
} elseif (isset($_GET['admin_id']))  {
    // an instructor_admin_list ID was submitted from the Instructor Admin form.
    // Branch on indicated action:
    
    if (isset($_GET['action'])) {
        
        $action = $_GET['action'];
        $ADMIN_ID=$_GET['admin_id'];
        
        if ($action == "edit" ) {
            get_instructor_admin_variables($ADMIN_ID);
            header('Location: ../instructorDB_admin/instructor_admin_form.php');            
        } elseif ($action == "delete") {
            
            $ihcid = $ADMIN_ID;
            
            // Delete the indicated record in cmc_instructor and related table, instructors_have_classes:
            $del_query = "DELETE FROM Instructors WHERE idInstructors=".$ihcid.";";
            
            //echo "Here's the delete query: ".$del_query;
            
            $result = mysqli_query($dbh,$del_query);
            if (!$result) {
                echo "Error: The attempt to delete an Instructor has failed. Here is the query: <br>".$del_query."<br>";
                die('Here is the error message: ' . mysqli_error());
            } else {
                // Return to instructor_admin_list with message
                header('Location: ../instructorDB_admin/instructor_admin_list.php?message=delete_success');
            }
            
        } else {
            die('Unknown action passed with admin_id to instructorDB_controller.php; aborting...');          
        }
    } else {
        die('No action variable passed with admin_id to instructorDB_controller.php; aborting...');
    }

/*************************************************    
* Instructor Admin form
* the Save button was clicked from the .
* Save the Instructor Admin data.
**************************************************/
} elseif (isset($_POST['submitInstructorAdmin']))  {
    
    if (!save_instructor_admin_variables()) {
        echo "Error! Instructor Admin data not saved!";
    } else {
        // Return to instructor_admin_edit_form and display confirmation message:
        header('Location: ../instructorDB_admin/instructor_admin_form.php?message=saved');
    }
    
/*************************************************
*    origin: instructor_admin_export_form.php
*    cause:  Export button clicked
*    action: create file to download that contains selected instructors
**************************************************/
} elseif (isset($_POST['submitAdminExport']))  {
    
    global $dbh;

    // the Export button was clicked in the Admin Export form.
    // This function creates a file for the use to download.
    
    // Determine which schools were checked to include:        
    // Create the SQL to retrieve the requested schools:
    
    $sql = "SELECT fname, lname, email, phone1 FROM Instructors WHERE active";

    // if all_instructors was selected, the sql is complete; otherwise, add where conditions to query
    // based on checkboxes that were selected.
    
    if (!isset($_POST['all_instructors'])) {
        
        if (isset($_POST['avi_instructor'])) {    
            $sql = $sql." AND avi_instructor";
        }
        
        if (isset($_POST['ice_instructor'])) {
                $sql = $sql." AND ice_instructor";
        }
        
        if (isset($_POST['rock_instructor_trad'])) {
            $sql = $sql." AND rock_instructor_trad";
        }
        
        if (isset($_POST['rock_instructor_sport'])) {
            $sql = $sql." AND rock_instructor_sport";
        }
        
        if (isset($_POST['ski_instructor'])) {
            $sql = $sql." AND ski_instructor ";
        }
        
        if (isset($_POST['snow_instructor'])) {
            $sql = $sql." AND snow_instructor";
        }
    }
    $sql = $sql.";";
    
    
    // Open the output file:       
    $temp_dir = sys_get_temp_dir();
    $filename = $temp_dir."/instructor_export.csv";
    $handle = fopen($filename,"w");

    // Run the query & read output into the file:
    $result = mysqli_query($dbh,$sql);
    
    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {
            $line = $row["fname"].",".$row["lname"].",".$row["email"]."\n";
            //echo $line;
            fwrite($handle,$line);
        }
        
        fclose($handle);
    } else {
        die('Error in submitAdminExport query: '.$sql.' Error message:'.mysqli_error());
    }

    // Force the download
    header("Content-Disposition: attachment; filename=\"".$filename."\"");
    header("Content-Length: " . filesize($filename));
    header("Content-Type: application/octet-stream;");
    if (!readfile($filename)) {
        echo "Something went wrong trying to download instructor_export.csv file...aborting.";
        quit;
    }

    
    // Return to the form 
    header('Location: ../instructorDB_admin/instructor_admin_export_form.php');
    //echo "\n\n<<< END OF QUERY >>>";
    //quit;
    
} else {
    echo "Unrecognized message sent to InstructorDB_controller; aborting.<br>";
    quit;
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *  Below here are functions called above:
 *
 *  is_valid_cmcmemberid()
 *  get_instructor_variables()
 *  save_instructor_variables()
 *  save_instructor_admin_variables()
 *  delete_rec(id,table)
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

function is_valid_cmcmemberid($param_id) {
    global $dbh;
    // Test if this CMC Member ID exists in the database
    
    $query = "SELECT * FROM Instructors WHERE CMCMemberID="
    .$param_id.
    ";";
    
    $result = mysqli_query($dbh,$query);
    if ($result) {
        $rtnval = true;
    } else {
        $rtnval = false;
    }
    return $rtnval;
}

function get_instructor_admin_variables($param_id) {
    
    global $dbh;
    
    $query = "SELECT idInstructors AS ID,
    FName,
    LName,
    avi_instructor,
    hiking_instructor,
    ice_instructor,
    rock_instructor_trad,
    rock_instructor_sport,
    ski_instructor,
    snow_instructor
    FROM Instructors WHERE idInstructors=$param_id LIMIT 1";

    
    //if ($_SESSION["debug"]) {
    //    echo "query = ".$query."<br>";
    //}

    
    $result = mysqli_query($dbh,$query);
    if ($result) {
        $row = mysqli_fetch_array($result);
        $ID = $row[0];
        $fname = $row[1];
        $lname = $row[2];
        $avi_instructor = $row[3];
        $hiking_instructor = $row[4];
        $ice_instructor = $row[5];
        $rock_instructor_trad = $row[6];
        $rock_instructor_sport = $row[7];
        $ski_instructor = $row[8];
        $snow_instructor = $row[9];
    } else {
        echo "There was a problem getting the session variables in function get_instructor_admin_variables()";
        quit;
    }
    
       // Assign retrieved data to SESSION variables to display in form:
    
    $_SESSION["ID"] = $ID; // index key...important!
    $_SESSION["fname"] = $fname;
    $_SESSION["lname"] = $lname;
    $_SESSION["avi_instructor"] = $avi_instructor;
    $_SESSION["hiking_instructor"] = $hiking_instructor;
    $_SESSION["ice_instructor"] = $ice_instructor;
    $_SESSION["rock_instructor_trad"] = $rock_instructor_trad;
    $_SESSION["rock_instructor_sport"] = $rock_instructor_sport;
    $_SESSION["ski_instructor"] = $ski_instructor;
    $_SESSION["snow_instructor"] = $snow_instructor;

    
}


function get_instructor_variables($param_id, $VIA_MEMBERID) {
    global $dbh;
    
    //if ($_SESSION["debug"]) {
    //    echo "into get_instructor_variables: param_id = ".$param_id."....<br>";
    //}
    
    if (!isset($param_id) || trim($param_id)==="") {

         if ($debug) {
            echo "the empty branch of param_id was selected....<br>";
         }
        
        // Create empty variables for the instructor_form
        $fname = "";
        $lname = ""; 
        $email = ""; 
        $phone1 = ""; 
        $phone2 = ""; 
        $comment = ""; 
        $wfa_certified = "";
        $wfa_expire_date = "";
        $photo = ""; 
        $photo_caption = ""; 
        $CMCmemberID = "";
        $active = "";
        $show_profile = "";
        
    } else {


        //if ($_SESSION["debug"]) {
        //    echo "the non-empty branch of param_id was selected...VIA_MEMBERID = ".$VIA_MEMBERID."<br>";
        //}

        
        // Retrieve data using the method indicated by the value of VIA_MEMBERID
        // and assign to variables for the instructor_form
    
        /* get Instructor fields & array index #:
        idInstructors      0
        fname              1
        lname              2
        email              3
        phone1             4
        phone2             5
        comment            6
        wfa_certified      7
        wfa_expire_date    8
        bfa_certified      9
        bfa_expire_date    10
        instructor_photo   11
        photo_caption      12
        create_date        13
        last_timestamp     14
        CMCmemberID        15
        active             16
        show_profile       17
        idGroups           18
        */
        
        // This function can be called with either the CMCMemberID or the database-assigned ID, i.e. the field idInstructors.
        // Note that CMCMemberID is a string, whereas idInstructors is an integer....
        if ($VIA_MEMBERID == "TRUE") {
            $query = "SELECT * FROM Instructors WHERE CMCMemberID='$param_id' LIMIT 1";
        } else {
            $query = "SELECT * FROM Instructors WHERE idInstructors=$param_id LIMIT 1";
        }
        
        //if ($_SESSION["debug"]) {
        //    echo "query = ".$query."<br>";
        //}

        
        $result = mysqli_query($dbh,$query);
        if ($result) {
            $row = mysqli_fetch_array($result);
            $ID = $row[0];
            $fname = $row[1];
            $lname = $row[2];
            $email = $row[3];
            $phone1 = $row[4];
            $phone2 = $row[5];
            $comment = $row[6];
            $wfa_certified = $row[7];
            $wfa_expire_date = $row[8];
            $bfa_certified = $row[9];
            $bfa_expire_date = $row[10];
            $photo = $row[11];
            $photo_caption = $row[12];
            $create_date = $row[13];
            $last_timestamp = $row[14];
            $CMCmemberID = $row[15];
            $active = $row[16];
            $show_profile = $row[17];
        } else {
            echo "Error! Result of this query failed in get_instructor_variables in instructorDB_controller: ".$query."<br>";
        }
    }
    
    // Assign retrieved data to SESSION variables to display in form:
    
    $_SESSION["ID"] = $ID; // index key...important!
    $_SESSION["fname"] = $fname;
    $_SESSION["lname"] = $lname;
    $_SESSION["email"] = $email;
    $_SESSION["phone1"] = $phone1;
    $_SESSION["phone2"] =$phone2;
    $_SESSION["comment"] = $comment;
    $_SESSION["wfa_certified"] = $wfa_certified;
    $_SESSION["wfa_expire_date"] = $wfa_expire_date;
    $_SESSION["bfa_certified"] = $bfa_certified;
    $_SESSION["bfa_expire_date"] = $bfa_expire_date;
    $_SESSION["photo"] = $photo;
    $_SESSION["photo_caption"] = $photo_caption;
    $_SESSION["CMCmemberID"] = $CMCmemberID;
    $_SESSION["active"] = $active;
    $_SESSION["show_profile"] = $show_profile;
    
}

function save_instructor_variables() {
    // Saves current data passed via _POST variables.
    global $dbh;
       
    /*
     * Need to translate checkbox values to boolean manually. If a checkbox gets checked, the POST value is on; otherwise, the POST
     * variable doesnt exist.
     */
    $active = isset($_POST["active"]) ? 1 : 0;
    $show_profile = isset($_POST["show_profile"]) ? 1 : 0;
    $wfa_certified = isset($_POST["wfa_certified"]) ? 1 : 0;
    $bfa_certified = isset($_POST["bfa_certified"]) ? 1 : 0;
    
    // Guard against special characters in string variables.
    $comment = str_replace("\'", "\'\'",$_POST["comment"]);
    $fname = str_replace("\'", "\'\'",$_POST["fname"]);
    $lname = str_replace("\'", "\'\'",$_POST["lname"]);
    
    // Check the flag for adding a new instructor: 
    
    if (!$_SESSION["is_new"]) {
        
        // This is an update of an existing instructor.
        // Apply a mysql function that escapes any special characters in the comment variable.
     
        $query = "UPDATE Instructors SET
            fname = '".$fname."',
            lname = '".$lname."',
            CMCmemberID = '".$_POST["CMCmemberID"]."',
            phone1 = '".$_POST["phone1"]."',
            phone2 = '".$_POST["phone2"]."',
            email = '".$_POST["email"]."',
            comment = '".$comment."',
            wfa_certified = ".$wfa_certified.",
            wfa_expire_date = '".$_POST["wfa_expire_date"]."',
            bfa_certified = ".$bfa_certified.",
            bfa_expire_date = '".$_POST["bfa_expire_date"]."',
            active = ".$active.",
            show_profile = ".$show_profile.",
            last_timestamp = NOW()
            WHERE idInstructors = ".$_SESSION["ID"].";";
                        
    } else {
        
        // This is a new instructor; insert new record.
        // Apply a mysql function that escapes any special characters in the comment variable.
        
        $query = "INSERT INTO Instructors
        (fname, lname, CMCmemberID, phone1, phone2, email, comment, wfa_certified, wfa_expire_date, active, show_profile, last_timestamp, create_date)
        VALUES (
        '".$fname."',
        '".$lname."',
        '".$_POST["CMCmemberID"]."',
        '".$_POST["phone1"]."',
        '".$_POST["phone2"]."',
        '".$_POST["email"]."',
        '".$comment."',
        ".$wfa_certified.",
        '".$_POST["wfa_expire_date"]."',
        ".$active.",
        ".$show_profile.",
        NOW(),
        NOW()
        )";
        
    }
    
    $debug = 0;
    if ($debug) {
        echo "The query is: <br>".$query;
        echo "<p>_POST variables: <br>";
        var_dump($_POST);
        echo "<br>";
        exit;
    }
       
    $result = mysqli_query($dbh,$query);
    if ($result) {
        return true;
    } else {
        //return false;
        echo "The query failed. Here is the query: ".$query."<br>";
        echo "The error message: ";
        die(mysqli_e);
    }
    
    // Finally, assign _POST data to SESSION variables so they display when returning to the form:
    
    $_SESSION["fname"] = $_POST["fname"];
    $_SESSION["lname"] = $_POST["lname"];
    $_SESSION["email"] = $_POST["email"];
    $_SESSION["phone1"] = $_POST["phone1"];
    $_SESSION["phone2"] = $_POST["phone2"];
    $_SESSION["comment"] = $_POST["comment"];
    $_SESSION["wfa_certified"] = $_POST["wfa_certified"];
    $_SESSION["wfa_expire_date"] = $_POST["wfa_expire_date"];
    $_SESSION["bfa_certified"] = $_POST["bfa_certified"];
    $_SESSION["bfa_expire_date"] = $_POST["bfa_expire_date"];
    $_SESSION["photo"] = $_POST["photo"];
    $_SESSION["photo_caption"] = $_POST["photo_caption"];
    $_SESSION["CMCmemberID"] = $_POST["CMCmemberID"];
    $_SESSION["active"] = $_POST["active"];
    $_SESSION["show_profile"] = $_POST["show_profile"];
    
}

function save_instructor_admin_variables() {
    // Saves current data passed via _POST variables.
    global $dbh;
       
    /*
     * Need to translate checkbox values to boolean manually. If a checkbox gets checked, the POST value is on; otherwise, the POST
     * variable does not exist.
     */
    $avi_instructor = isset($_POST["avi_instructor"]) ? 1 : 0;
    $hiking_instructor = isset($_POST["hiking_instructor"]) ? 1 : 0;
    $ice_instructor = isset($_POST["ice_instructor"]) ? 1 : 0;
    $rock_instructor_trad = isset($_POST["rock_instructor_trad"]) ? 1 : 0;
    $rock_instructor_sport = isset($_POST["rock_instructor_sport"]) ? 1 : 0;
    $ski_instructor = isset($_POST["ski_instructor"]) ? 1 : 0;
    $snow_instructor = isset($_POST["snow_instructor"]) ? 1 : 0;
            
    $query = "UPDATE Instructors SET
        avi_instructor = ".$avi_instructor.",
        hiking_instructor = ".$hiking_instructor.",
        ice_instructor = ".$ice_instructor.",
        rock_instructor_trad = ".$rock_instructor_trad.",
        rock_instructor_sport = ".$rock_instructor_sport.",
        ski_instructor = ".$ski_instructor.",
        snow_instructor = ".$snow_instructor." 
        WHERE idInstructors = ".$_SESSION["ID"].";";

    $result = mysqli_query($dbh,$query);
    // Finally, assign _POST data to SESSION variables so they display when returning to the form:
    
    $_SESSION["avi_instructor"] = $_POST["avi_instructor"];
    $_SESSION["hiking_instructor"] = $_POST["hiking_instructor"];
    $_SESSION["ice_instructor"] = $_POST["ice_instructor"];
    $_SESSION["rock_instructor_trad"] = $_POST["rock_instructor_trad"];
    $_SESSION["rock_instructor_sport"] = $_POST["rock_instructor_sport"];
    $_SESSION["ski_instructor"] = $_POST["ski_instructor"];
    $_SESSION["snow_instructor"] = $_POST["snow_instructor"];
 
    if ($result) {
        return true;
    } else {
        echo "Query to save Instructor Admin data failed in instructorDB_controller.php: query =<br>".$query."<br>";
        return false;
    }
 
 }           
            

?>