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
          
        table { border-collapse: collapse;}
        tr { border: solid; border-width: 1px 0; }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body onload=chk_message()>
  <? $header_image = "423-427-web.jpg"; ?>
  <? include('body_header.php'); ?>
<div id='content'>
<table width="850" cellspacing="10" cellpadding="0" border="1" id="instruct_tbl">
    <tr><td height="10" align="center" valign="top"><p><span class="head">BMS Instructor Administration Page</span></p></td></tr>
    <tr><td align="center"><span class="smallhead">alphabetical by first name</span></td></tr>
    <tr><td align="center"><span class="smallhead">checkboxes show respective schools; select Edit to change</span></td></tr>
    <tr><td height="*" align="center" valign="middle">
      <table cellspacing="10" cellpadding="3" border='0'>
        <tr><td align="center" colspan=9><span class="head">Instructors</span></td></tr>
            <?php 
          
                $dbh = db_connect() or die('I cannot connect to the database because: ' . mysqli_error());                
                // get table data
                // The table is 9 columns wide, with these headings (first column hidden):
                // 0     1         2           3        4       5       6      7         8      9
                // ID | Name | Avalanche  |  Hiking |  Ice  |  Rock  | Ski  | Snow   | Edit | Delete |
                // Note: column 0 (hidden) is used to send the database ID when a name is selected to delete.
                // (This is where the CMC instructors could be displayed by schools could displayed, by modifying the SQL.)
                $query1 = "SELECT idInstructors AS ID, LName, FName, avi_instructor, hiking_instructor, ice_instructor,
                rock_instructor_trad,rock_instructor_sport,ski_instructor,snow_instructor FROM Instructors order by FName, LName ";
                        
                $result1 = mysqli_query($dbh, $query1);
                if ($result1) {
                    
                    while($row = mysqli_fetch_array($result1,MYSQLI_NUM)) {

                        $ID = $row[0];
                        $lname = $row[1];
                        $fname = $row[2];
                        $avi_instructor = $row[3];
                        $hiking_instructor = $row[4];
                        $ice_instructor = $row[5];
                        $rock_instructor_trad = $row[6];
                        $rock_instructor_sport = $row[7];
                        $ski_instructor = $row[8];
                        $snow_instructor = $row[9];
                        
                        
                        // Now echo these boolean values, converting to disabled check boxes. User must select Edit to change.
                        $this_row = "<tr><td style='display:none'>$ID</td></td><td align=\"left\">$fname $lname</td>";
                        
                        // Avi
                        if ($avi_instructor) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='avi'  checked >avi</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='avi'  >avi</td> ";                
                        }
                        // Hiking
                        if ($hiking_instructor) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='hike'  checked >hike</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='hike'  >hike</td> ";                
                        }
                        // Ice
                        if ($ice_instructor) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='ice'  checked >ice</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='ice'  >ice</td> ";                
                        }
                        // Rock Trad
                        if ($rock_instructor_trad) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='rock_trad'  checked >rock trad</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='rock_trad'  >rock trad</td> ";                
                        }
                        // Rock Sport
                        if ($rock_instructor_sport) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='rock_sport'  checked >rock sport</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='rock_sport'  >rock sport</td> ";                
                        }
                        // Ski
                        if ($ski_instructor) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='ski'  checked >ski</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='ski'  >ski</td> ";                
                        }
                        // Snow
                        if ($snow_instructor) {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='snow'  checked >snow</td> ";                
                        } else {
                            $this_row = $this_row."<td><input type='checkbox' onclick='return false'  name='snow'  >snow</td> ";                
                        }
                        // Add Edit & Delete buttons and close the row.
                        $this_row = $this_row."<td><a href='../instructorDB_protected/instructorDB_controller.php?admin_id=$ID&action=edit'>Edit</a></td><td><a class='delete_button' >Delete</a></td></tr>";
                        echo $this_row;
                      }
                        
                    } else {
                        echo "Your database connection failed; here is the failed query: <br>".$query1;
                    }
 
            ?>
      </table>
    </td></tr>
    <tr>
        <td align="center" valign="bottom"><p><a href="../instructorDB_protected/instructorDB_top.php" target="_self">Return to top</a></td>
    </tr>
</table>
</div>
<div id='content'>
<font size="-1"><i>
</i></font>
</div>
  <? include('../../includes/body_footer.php'); ?>
</body>
<script>
    
    // Display confirmation message after data operation performed
    <?php
        if (isset($_GET['message']) && $_GET['message']== "delete_success") {
            echo "var message = 'Record deleted';";
        }
    ?>
    
    function chk_message() {
         if (!(typeof(message) === 'undefined')  )  {
            alert(message);
         }
    }

    $( document ).ready(function() {
        
        
        $('.delete_button').click(function() {
            // Get name and id from row clicked
            
            var $tr = $(this).closest('tr');
            var id = $tr.find('td:first-child').text();
            var name = $tr.find('td:nth-child(2)').text();
            
            //alert(msg);

            if ( confirm('Really delete '+name+'?') ) {
                //alert('here id = '+id+' will be used to delete the record...');
                window.location.href = "http://proto.cmcboulder.org/instructorDB_public/instructorDB_protected/instructorDB_controller.php?admin_id="+id+"&action=delete";
            }
        });        
    });
</script>
</html>

