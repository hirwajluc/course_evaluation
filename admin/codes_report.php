<?php
session_start(); // Use session variable on this page. This function must put on the top of page.
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Students Codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<style type="text/css" media="print">
.hide{display:none}
</style>
<script type="text/javascript">
function printpage() {
document.getElementById('printButton').style.visibility="hidden";
window.print();
document.getElementById('printButton').style.visibility="visible";  
}
</script>
<body>
<!--
<input name="print" type="button" value="Print" id="printButton" onClick="printpage()">
-->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
      <table width="595" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="30" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr><td height="30" rowspan="3"align="left"><img src="images/logo.png" width="163" height="68" border="0" alt="Win Win Logo">
			<font style='color:#165C9C; font-weight:bold;'>
			LIST OF STUDENTS CODES
			</font>
			</td>
             
             </tr>
           
           
          </table></td>
        </tr>
        <tr>
          <td width="45"><hr>
		  <input style='width:90px; height:50px;border-radius:5px 0px 10px 1px; background-color:#165C9C; color:white; font-weight:bold;' name="print" type="button" value="Print Here" id="printButton" onClick="printpage()">
		  </td>
        </tr>
        <tr>
          <td height="20"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
              
          </table></td>
        </tr>
        <tr>
          <td width="45"><hr></td>
        </tr>
        <tr>
          <td><table width="100%"  border="0" BORDER=1 CELLPADDING=1 CELLSPACING=1 
    RULES=ROWS FRAME=BOX>
              <tr bgcolor="#66ff33">
                <td><strong>No</strong></td>
                <td><strong>Codes </strong></td>
				           
               
              </tr>
			 <!-- <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
			  -->
			  <?php 
				   
			  $result = $connPDO->query("SELECT * FROM tbl_codes where used=0");
			  
			  $data = $result -> rowCount();
				while ($line = $result -> fetch(PDO::FETCH_ASSOC)) {
				?>
			
				<tr>
                <td><?php echo $line['id'];?></td>
                <td><?php echo $line['code']; ?></td>  
							
                           
              </tr>
			  	

<?php
	
}
		  ?>
		
          </table></td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
    </table></td>
  </tr>
</table>

</body>
</html>
