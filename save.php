<?php
include 'db.php';
$table = isset($_POST['table']) ? $_POST['table'] : '';
if (!$table) { header('Location: index.php'); exit; }

$allowed = ['livestock','processing','storage','stock','fifo','sensor','yields','recall'];
if(!in_array($table, $allowed)) { header('Location: index.php'); exit; }

// build column list dynamically from POST (except 'table' and empty values)
$cols = [];
$vals = [];
foreach($_POST as $k=>$v){
    if(in_array($k, ['table'])) continue;
    // skip empty fields to allow defaults
    if($v === '') continue;
    $cols[] = "`".mysqli_real_escape_string($conn, $k)."`";
    $vals[] = "'".mysqli_real_escape_string($conn, $v)."'";
}
if(count($cols) > 0){
    $sql = "INSERT INTO `$table` (".implode(',', $cols).") VALUES (".implode(',', $vals).")";
    mysqli_query($conn, $sql);
}
header('Location: index.php');
exit;
?>
