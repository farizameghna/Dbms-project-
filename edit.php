<?php
include 'db.php';
$table = $_REQUEST['table'] ?? '';
$id = intval($_REQUEST['id'] ?? 0);
$allowed = ['livestock','processing','storage','stock','fifo','sensor','yields','recall'];
if(!in_array($table, $allowed) || $id <= 0){ header('Location: index.php'); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // update
    $sets = [];
    foreach($_POST as $k=>$v){
        if(in_array($k, ['table','id'])) continue;
        $sets[] = "`".mysqli_real_escape_string($conn,$k)."` = '".mysqli_real_escape_string($conn,$v)."'";
    }
    if(count($sets)){
        $sql = "UPDATE `$table` SET ".implode(',', $sets)." WHERE id = ".intval($_POST['id']);
        mysqli_query($conn, $sql);
    }
    header('Location: index.php'); exit;
}

// fetch current
$res = mysqli_query($conn, "SELECT * FROM `$table` WHERE id = $id LIMIT 1");
$row = mysqli_fetch_assoc($res);
?>
<!DOCTYPE html><html><head><meta charset="utf-8"><title>Edit</title></head><body>
<h2>Edit <?=htmlspecialchars($table)?> #<?=intval($id)?></h2>
<form method="post">
<input type="hidden" name="table" value="<?=htmlspecialchars($table)?>">
<input type="hidden" name="id" value="<?=intval($id)?>">
<?php
if($row){
    foreach($row as $k=>$v){
        if(in_array($k, ['id'])) continue;
        $safe = htmlspecialchars($v);
        echo '<div style="margin:6px 0;"><label>'.htmlspecialchars($k).': </label>';
        echo '<input type="text" name="'.htmlspecialchars($k).'" value="'.$safe.'"></div>';
    }
}
?>
<button type="submit">Save</button> <a href="index.php">Cancel</a>
</form>
</body></html>
