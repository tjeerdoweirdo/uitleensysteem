<?php
require_once 'includes/db_connection.php';

               $sql = "SELECT * FROM items;";
               $result = mysqli_query($conn, $sql);

                while($row = mysqli_fetch_assoc($result));
               
               ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Importeer Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </link>
    <title>Document</title>
</head>

<body>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal1">Meer...</button>
    <!-- Pop up !MODAL! -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal1Label">
                    <?php
                        while($row = mysqli_fetch_assoc($result)); {?>
                    <?php echo $row['itemName']; ?>

                </h1>
                <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <!-- Item inhoud --> 
               <?php echo $row['itemName']?>
               <?php echo $row['itemNumber']?>
               <?php echo $row['itemDout']?>
               <?php echo $row['itemDin']?>
               <?php echo $row['itemDescription']?>
               <?php echo $row['itemState']?>
               <?php echo $row['itemPicture']?>
        

               <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                <button type="button" class="btn btn-primary">Opslaan</button>
            </div>
        </div>
    </div>
    </div>

</body>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>

</html>