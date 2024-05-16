<?php
include './admin/includes/dbConnectPDO.php';
global $connPDO;
$id = (int) $_GET['id'];

$sql = "";
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $sql = "DELETE FROM tbl_comments where comment_id = $id";
} else if (isset ($_GET['action']) && $_GET['action'] == 'edit'){
    $name = $_GET['name'];
    $name = trim($name);
    $sql = "UPDATE tbl_comments SET comment_value = '$name' where comment_id = $id";
}
if (strlen($sql) > 0 && $id > 0) {
    $connPDO->exec($sql);
    echo "success";
}
?>