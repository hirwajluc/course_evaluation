<?php

include 'CodeGenerator.php';

$ps = new CodeGenerator();

$it = 3;
$et = 2;
$ae = 1;

$group_a = 1;
$group_b = 2;
$group_c = 3;

$sem1 = 1;
$year1 = 1;
$year2 = 2;

/* generating codes for AE department */
$codes[] =  $ps->generateCode($ae,$year2,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($ae,$year2,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($ae,$year2,$sem1, $group_c, 50);
$codes[] =  $ps->generateCode($ae,$year1,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($ae,$year1,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($ae,$year1,$sem1, $group_c, 50);

/* generating codes for ET department */
$codes[] =  $ps->generateCode($et,$year2,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($et,$year2,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($et,$year2,$sem1, $group_c, 50);
$codes[] =  $ps->generateCode($et,$year1,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($et,$year1,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($et,$year1,$sem1, $group_c, 50);

/* generating codes for IT department */
$codes[] =  $ps->generateCode($it,$year2,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($it,$year2,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($it,$year2,$sem1, $group_c, 50);
$codes[] =  $ps->generateCode($it,$year1,$sem1, $group_a, 50);
$codes[] =  $ps->generateCode($it,$year1,$sem1, $group_b, 50);
$codes[] =  $ps->generateCode($it,$year1,$sem1, $group_c, 50);


$i = 1;
mysql_connect("localhost", "root", "");
mysql_select_db('course_evaluation');
while(list ($x, $y) = each ($codes)){
    foreach ($y as $a)
        
   mysql_query ("INSERT INTO tbl_codes SET code = '$a'");
   print "$a<br />";
}
?>
