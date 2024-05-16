<?php
if (isset($_POST['year'])) {
	$yr = $_POST['year'];
	?>
	<option selected disabled hidden>--Choose Semester--</option>
    <option value="1">Semester 1</option>
    <option value="2">Semester 2</option>
	<?php
}
?>