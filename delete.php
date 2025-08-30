<?php
include 'db.php';
if($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
$table = $_POST['table'] ?? '';
$id = intval($_POST['id'] ?? 0);
$allowed = ['livestock','processing','storage','stock','fifo','sensor','yields','recall'];
if($id > 0 && in_array($table, $allowed)){
    $sql = "DELETE FROM `$table` WHERE id = $id LIMIT 1";
    mysqli_query($conn, $sql);
}
header('Location: index.php');
exit;
?>
