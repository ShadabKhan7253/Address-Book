<?php
require("includes/functions.inc.php");

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    $rows = db_select("SELECT * FROM contacts WHERE id = $id");
    if(!$rows) {
        dd(db_error());
    }
    $image_name = $rows[0]['image_name'];
    $image_name = get_image_name($image_name,$id);
    unlink("images/users/$image_name");
    $query = prepare_delete_query("contacts","id = $id");
    db_query($query);
    header("Location: index.php?op=delete&status=success");
}
?>