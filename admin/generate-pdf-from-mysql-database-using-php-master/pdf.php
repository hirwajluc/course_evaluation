<?php
//Starting by Blaise
include('database.php');

//Choose the Semester
$sem = $_GET['sem'];
$ac_year = $_GET['yr'];
$database = new Database();
$code_part = $dept.$level.$sem.$group;
$result = $database->runQuery("SELECT * FROM tbl_codes where academic_year = '{$ac_year}' AND code like '{$code_part}%' ORDER BY id asc");
$header = $database->runQuery("SELECT UCASE(`COLUMN_NAME`) 
FROM `INFORMATION_SCHEMA`.`COLUMNS` 
WHERE `TABLE_SCHEMA`='course_evaluation' 
AND `TABLE_NAME`='tbl_codes'
and `COLUMN_NAME` in ('id', 'academic_year','code','used')");

require('fpdf/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();

//Blaise
// Display the class Title
$pdf->SetFont('Arial','B',14);
echo "<h1>".$pdf->Cell(0,0,$head,0,0,"C")."</h1>";
$pdf->Ln(12);

$link = "(Link: http://192.168.9.5/course_evaluation/)";
$pdf->SetFont('Arial','B',13);
echo "<h1>".$pdf->Cell(0,0,$link,0,0,"C")."</h1>";
$pdf->Ln(8);

//Display The Table
$pdf->SetFont('Arial','B',12);
foreach($header as $heading) {
	foreach($heading as $column_heading)
		$pdf->Cell(45,10,$column_heading,1);
}
foreach($result as $row) {
	$pdf->Ln();
	foreach($row as $column)
		$pdf->Cell(45,8,$column,1);
}
$pdf->Output('', $fname, false);
?>