<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <? $page_title = "Instructor Information"; ?>
    <? include($_SERVER['DOCUMENT_ROOT'].'/includes/html_head.php'); ?>


<script type="text/javascript" language="JavaScript">


function focusForm() {
	if(document.memberValidation) {
		document.memberValidation.CMCMemberID.focus();
		return;
	}
	return;
}


form.onSubmit = function(evnt) {
    // Validate that CMC Member ID is not empty; a database validation is done in the call to instructorDB_controller.php.
    var rtnval = true;
    if (document.CMCMemberID === "") {
        alert("Please enter a CMC Member ID");
        rtnval = false;
    } 
    return rtnval;
}

</script>
</head>

<body onload="focusForm();">
  <? $header_image = "423-427-web.jpg"; ?>
  <? include($_SERVER['DOCUMENT_ROOT'].'/includes/body_header.php'); ?>
    <div id='content'>
        <form name="memberValidation" action="instructorDB_controller.php" method="post">
            <table width="850" cellspacing="10" cellpadding="10" border="0">
                <tr><td align="center" valign="top" colspan="3"><p><span class="head">Colorado Mountain Club<br>Boulder Group Instructor Database</span></p></td></tr>
                <tr>
                    <td align="center"><span class="head">Enter the CMC Member ID:</span></td>
                    <td align="left"><input class="head" type="text" size="6" maxlength="6" name="CMCMemberID" /></td>
                    <td align="left"><input class="head" type="submit" name="submitMemberID" value="Submit" /></td>
                </tr>    
            </table>
        </form>
        <center>
        <p><a href="instructorDB_top.php" target="_self">Return to top</a></p>
        <p><a href="instructor_list.php" target="_self">Return to Instructor List</a></p>
        </center>
    </div>
  <? include('../../includes/body_footer.php'); ?>
</body>
</html>
