<?php
require_once 'db_connection.php';

if(isset($_POST['savedata'])){
    
    $id = $_POST['update_id'];

    $item = $_POST['item'];
    $nummer = $_POST['nummer'];
    $datumin = $_POST['datumin'];
    $datumuit = $_POST['datumuit'];
    $omschrijving = $_POST['omschrijving'];
    $staat = $_POST['staat'];
    $foto = $_POST['foto'];
    
    if(isset($_FILES['foto']) && $_FILES['foto']['size'] > 0 && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto']['tmp_name'];
        $imageData = file_get_contents($foto);
        $imageData = addslashes($imageData);
        $updateImageQuery = "UPDATE items SET itemPicture='$imageData' WHERE itemId='$id'";
        $updateImageResult = mysqli_query($conn, $updateImageQuery);
    }
    
    $sql = "UPDATE items SET itemName='$item', itemNumber='$nummer', itemDin='$datumin', itemDout='$datumuit', itemDescription='$omschrijving', itemState='$staat' WHERE itemId='$id' ";
    $result = mysqli_query($conn, $sql);
    
    if($result){
        echo '<script> alert("Opgeslagen!"); </script>';
        header ("Location:../popuplog.php");
        exit();
    } else {
        echo '<script> alert("Opslaan niet gelukt"); </script>';
    }
}
?>
