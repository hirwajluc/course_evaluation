<?php
session_start(); // Use session variable on this page. This function must put on the top of page.
/*Include the default function file*/
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Evaluation Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" href="css/bootstrap.min.css">
<script type = 'text/javascript' language='javascript' src = 'js/jquery-2.0.3.min.js'></script>
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
<div class="container" style="width: 70%;">
  <div class="row">
    <img class="col-md-6" src="images/logo.png" alt="Win Win Logo">
    <div class="col-md-6">
      <center>
        <br><br>
        <font style='color:#165C9C; font-weight:bold;'>
          COURSE EVALUATION REPORT
        </font>
        <input style='width:90px; height:50px;border-radius:5px 0px 10px 1px; background-color:#165C9C; color:white; font-weight:bold; float: right;' name="print" type="button" value="Print Here" id="printButton" onClick="printpage()">
      </center>
    </div>
  </div>
  <h3>
    <form method="POST" action="">
      <label>Academic Year: </label>
      <select name="year" id="year">
        <option value=''>--Select Year--</option>
        <?php
        for ($year=date('Y'); $year >= 2020 ; $year--) { 
          ?>
          <option value="<?php echo $year.'-'.($year+1);?>"><?php echo $year."-".($year+1);?></option>
          <?php
        }
        ?>
      </select>
      <label>Semester: </label>
      <select name="semester" id="semester">
        <option selected disabled hidden>--Semester--</option>
      </select>
    </form>
  </h3>
  <div class="table-responsive" id="content_header">
    
  </div>
</div>
</body>
<script>
  $(document).ready(function () {
    $('#year').change(function () {
      var year = $('#year').val();
      if (year != ''){
        $.ajax({
          url:"evaluation_report_year.php",
          method:"POST",
          data:{year:year},
          success:function (data) {
            $('#semester').html(data);
          }
        })
      }
    });

    $('#semester').change(function () {
      var sem = $('#semester').val();
      var year = $('#year').val();
      if (sem != ''){
        $.ajax({
          url:"evaluation_report_semmester.php",
          method:"POST",
          data:{sem:sem, yr:year},
          success:function (data) {
            $('#content_header').html(data);
          }
        })
      }
    });
  });
</script>
</html>
