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
            font: bold 20px 'Trebuchet MS';
            padding: 4px;
            cursor: pointer;
        }
    </style>


</head>
<body onload=chk_message() >
  <?php
    $header_image = "423-427-web.jpg";
    include('body_header.php');
  ?>
    <div id='content'>
    <form name="InstructorForm" action="instructorDB_controller.php" method="post" onsubmit="return OnSubmitEvent(e);" enctype="multipart/form-data">
        
    <!--Note: this table should have 4 elements <td></td>, or equivalent colspan, per row.
        Tip: to modify the table structure, it helps to make borders visible (set border=1)
    -->
    <table style="width:100%" cellspacing="0" cellpadding="0" border="0"align="left">
        <tr>
            <td align="center" valign="top" colspan="4"><p><span class="head">Colorado Mountain Club - Boulder Group Instructor Database<br>Instructor Edit Form</span></p></td>
        </tr>
        <tr>
            <td><p><span class="notice">* required field</span></p></td>
        </tr>
        <tr>
            <td colspan="2"><span class="head" >* First Name: <input type="text" size="15" maxlength="15" name="fname" id="fname_id" value="<?php { echo $_SESSION["fname"]; } ?>" /></td>
            <td colspan="2"><span class="head" >* Last Name: <span class="notice"><input  type="text" size="15" maxlength="15" name="lname" id="lname_id" value="<?php { echo $_SESSION["lname"]; } ?>" /></span></td>
        </tr>
        <tr>
            <td colspan="2" ><p><span class="head">* CMC Member ID<span class="notice"></span>:&nbsp;<input  type="text" size="12" maxlength="12" name="CMCmemberID" id="cmcmember_id" value="<?php { echo $_SESSION["CMCmemberID"]; } ?>" </span></p></td>
            <td colspan="2" ><p><span class="head">Email: <input  type="text" size="20" maxlength="30" name="email" value="<?php { echo $_SESSION["email"]; } ?>" /></span></p></td>
        </tr>
        <tr>
            <td colspan="2" ><p><span class="head">Phone 1:&nbsp;<input  type="text" size="20" maxlength="20" name="phone1" value="<?php { echo $_SESSION["phone1"];} ?>" /></span></p></td>
            <td colspan="2" ><p><span class="head">Phone 2:&nbsp;<input  type="text" size="20" maxlength="20" name="phone2" value="<?php { echo $_SESSION["phone2"];} ?>" /></span></p></td>
        </tr>            
        <tr>
            <td colspan="4" ><p><span class="head">Comment:&nbsp;<input  type="text" size="50" maxlength="60" name="comment"  value="<?php { echo $_SESSION["comment"]; } ?>" /></span></p></td>
        </tr>            
        <tr>
            <td colspan="2"><p><span class="head">WFA* certified?:&nbsp;<input  type="checkbox" name="wfa_certified" <?php { if ($_SESSION["wfa_certified"]) {echo "checked"; } } ?> /></span></p></td>
            <td colspan="2"><p><span class="head">WFA Expire Date (YYYY-MM-DD):&nbsp;<input  type="date" size="10" maxlength="10" name="wfa_expire_date" value="<?php { echo $_SESSION["wfa_expire_date"]; } ?>" /></span></p></td>
        </tr>
        <tr>
            <td colspan="2"><p><span class="head">BFA* certified?:&nbsp;<input  type="checkbox" name="bfa_certified" <?php { if ($_SESSION["bfa_certified"]) {echo "checked"; } } ?> /></span></p></td>
            <td colspan="2"><p><span class="head">BFA Expire Date (YYYY-MM-DD):&nbsp;<input  type="date" size="10" maxlength="10" name="bfa_expire_date" value="<?php { echo $_SESSION["bfa_expire_date"]; } ?>" /></span></p></td>
        </tr>
        <tr>
            <td colspan="2"><p><span class="head">CPR certified?:&nbsp;<input  type="checkbox" name="cpr_certified" <?php { if ($_SESSION["cpr_certified"]) {echo "checked"; } } ?> /></span></p></td>            
            <td colspan="2"><p><span class="head">CPR Date (YYYY-MM-DD):&nbsp;<input  type="date" size="10" maxlength="10" name="cpr_cert_date" value="<?php { echo $_SESSION["cpr_cert_date"]; } ?>" /></span></p></td>
        </tr>
        <tr>
            <td colspan="4"><p><span class="head">Other first aid training:</span></p></td>           
        </tr>
        <tr>
            <td colspan="4"><p><span class="head">
                <textarea rows="2" cols="80">
                    <?php { echo $_SESSION["first_aid_descrip"]; } ?>
                </textarea>
            </td>
        </tr>
        <tr>
            <td ><p><span class="head">Show profile?<input  type="Checkbox" name="show_profile" <?php if ($_SESSION["show_profile"]) { echo "checked";} ?> /></span></p></td>
            <td ><p><span class="notice">Check this box to show your public profile; uncheck to hide.</span></p></td>
            <td ><p><span class="head">Active:<input  type="Checkbox" name="active" <?php if ($_SESSION["active"]) { echo "checked";} ?> /></span></p></td>
            <td ><p><span class="notice">Check this box to be considered an active instructor; uncheck to resign.</span></p></td>
        </tr>
        <tr>
            <td ><p><span class="head">Request deletion?<input  type="Checkbox" name="request_delete" <?php if ($_SESSION["request_delete"]) { echo "checked";} ?> /></span></p></td>
            <td ><p><span class="notice">Check to request your profile be deleted</span></p></td>
        </tr>
        <tr>
            <td ><p><span class="head">Request deletion?<input  type="Checkbox" name="request_delete" <?php if ($_SESSION["request_delete"]) { echo "checked";} ?> /></span></p></td>
            <td ><p><span class="notice">Check to request your profile be deleted</span></p></td>
        </tr>
        <tr>
            <td colspan="2" align="left"><small><i>* WFA = Wilderness First Aid, BFA = Basic First Aid</i></small></td>
        </tr>
        <tr>
            <td colspan="4" align="center"><? if ($show_msg) echo $message;  ?></td>
        </tr>      
        <tr>
            <td></td>
            <td align="center" valign="bottom" ><p><a href="instructorDB_top.php" target="_self">Return to top</a></td>
            <td align="center" valign="bottom" ><p><a href="instructor_list.php" target="_self">Return to Instructor List</a></td>
            <td></td>
        </tr>
    </table>
    </form>
    </div>
    <? include('../../includes/body_footer.php'); ?>
    
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
        
        function OnSubmitEvent(e) {
            
            e.preventDefault();

            //alert('into onsubmit event...');

            
            try {
                // Check that required fields are populated:
                // (RC, 2/25/16: does not seem to work?)
                
                var errmsg = "";
                
                if (document.getElementById('fname').value === "") {
                    errmsg =  errmsg + "First name cannot be empty!";
                    document.getElementById('fname').focus();
                }
                
                if (document.getElementById('lname').value === "") {
                    errmsg =  errmsg + "\nLast name cannot be empty!";
                    document.getElementById('lname').focus();
                }
                
                var teststg = "failed";
                if (document.getElementById('CMCmemberID').value === "") {
                    teststg = "passed";
                    errmsg =  errmsg + "\nCMC MemberID cannot be empty!";
                    document.getElementById('CMCmemberID').focus();
                }
                
                alert("Test string = " + teststg);
                alert("CMC MemberID length ==" + document.getElementById('CMCmemberID').length);

                if (errmsg.length() > 0) {
                    alert(errmsg);
                    return false;                 
                } else {
                    return true;
                }
                
            } catch (e) {
                throw new Error(e.message);
            }
        }
    </script>

</body>
</html>