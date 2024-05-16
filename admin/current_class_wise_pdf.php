<?php
//////////////////////////////////////////////////////////////////
//	current_class_wise_pdf.php
//
//////////////////////////////////////////////////////////////////
ob_start();
session_start();
/* Include the default function file */
require_once("includes/functions.php");
/* Include the database configuration file */
require_once ('../admin/includes/config.php');
include "../admin/includes/dbConnectPDO.php";
global $connPDO;
//require_once( "../cmnlib/cmn_func.php" );
//require_once( "../cmnlib/tcpdf/config/lang/eng.php" ); // TCPDF
//require_once('../tcpdf/examples/tcpdf_include.php');
require_once( "../cmnlib/tcpdf/tcpdf.php" );           // TCPDF

$acc_year = $_GET['accyear'];
//print "acc_year = ".$acc_year."<br>";
$pdf_max_item_line  =  32;
////////////////////////////////////////
$pdf = new TCPDF('P' , PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('TCT');
$pdf->SetTitle('Course evaluation pdf');

// set header and footer fonts
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
////$pdf->setHeaderFont(Array('cid0jp', '', 18)); //
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins  default value
//PDF_MARGIN_LEFT=15,  PDF_MARGIN_TOP=27, PDF_MARGIN_RIGHT=15
$pdf->SetMargins(10, 15, 10);

//$pdf->setPrintHeader(false);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);


///$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(true);

//set image scale factor
//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
//$pdf->setLanguageArray($l);

// ---------------------------------------------------------
// set font
$pdf->SetFont('', '', 10);

$searchQry  = " select * from tbl_subject ";
$searchQry .= "   INNER JOIN tbl_department ON tbl_subject.`subject_department_id` = tbl_department.department_id ";
$searchQry .= "   INNER JOIN tbl_teacher ON tbl_subject.`subject_teacher_id` = tbl_teacher.`teacher_id`           ";
$searchQry .= " WHERE tbl_subject.`subject_name` LIKE '{$keyword}%' ";
$searchQry .= "   OR  tbl_subject.`subject_code` LIKE '{$keyword}%' ";
$searchQry .= "     ORDER BY subject_id DESC  ";
//print "searchQry = ".$searchQry."<br>";
$searchRes     = $connPDO->query($searchQry);
$searchNumRows = $searchRes -> rowCount();

$old_dept_cd = "";
$table_width = 115;
$table_x     =  25;

$title_ary   = array( "Subject Name", "Dept Code", "Year", "Semester", "Teacher Name" );
$title_width = array(             90,          20,     15,        20 ,           45   );
$seq_no = 0;

while ($searchArr = $searchRes->fetch(PDO::FETCH_ASSOC))
{
	if (  ($seq_no == $pdf_max_item_line ) or ($seq_no ==0) ){
		$seq_no = 0;
		$pdf->AddPage();

		$pdf->SetFillColor(210); // gray 
		for( $i=0; $i< count($title_ary); $i++){
			$pdf->MultiCell(  $title_width[$i],   8, $title_ary[$i],  1, 'C', 1, 0, '', '', true, 1,false,false,  8, 'M',false );
		}
		
		$pdf->Ln();
	}
	$seq_no++;
	$detail_ary = array();
	$detail_ary[] = $searchArr["subject_name"];
	$detail_ary[] = $searchArr["department_code"];
	
	$detail_ary[] = $searchArr["subject_year"]." group ".$searchArr["subject_group"];
	$detail_ary[] = $searchArr["subject_semester"];
	$detail_ary[] = $searchArr["teacher_first_name"]."\n".$searchArr["teacher_last_name"];

	$pdf->SetFont('', '', 9);
	//for( $i=0; $i< count($detail_ary); $i++){
	//	$pdf->MultiCell(  $title_width[$i],   8, $detail_ary[$i],  1, 'C', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	//}
	$pdf->MultiCell(  $title_width[0],   8, $detail_ary[0],  1, 'L', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	$pdf->MultiCell(  $title_width[1],   8, $detail_ary[1],  1, 'C', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	$pdf->MultiCell(  $title_width[2],   8, $detail_ary[2],  1, 'C', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	$pdf->MultiCell(  $title_width[3],   8, $detail_ary[3],  1, 'C', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	$pdf->MultiCell(  $title_width[4],   8, $detail_ary[4],  1, 'L', 0, 0, '', '', true, 1,false,false,  8, 'M',false );
	$pdf->Ln();
}
// ---------------------------------------------------------
//Close and output PDF document
$pdf->Output('current_class_wise_pdf.pdf', 'I');
//============================================================+
// END OF FILE
//============================================================+
?>