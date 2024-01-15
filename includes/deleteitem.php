<?php
require_once 'db_connection.php';

if(isset($_POST['deleteitem'])){
    $id = $_POST['delete_id'];
    echo "Item ID to delete: " . $id; // Add this line for debugging


    $sql = "DELETE FROM items WHERE itemId='$id'";
    $result = mysqli_query($conn, $sql);

    if($result){
        echo '<script> alert("Item verwijderd!"); </script>';
        // header("Location:../popuplog.php");
        exit();
    } else {
        echo '<script> alert("Verwijderen niet gelukt: ' . mysqli_error($conn) . '"); </script>';
    }
}
