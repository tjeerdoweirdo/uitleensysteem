<?php
require_once 'includes/db_connection.php';

$sql = "SELECT * FROM items;";
$result = mysqli_query($conn, $sql);


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
    <?php if ($result) {

        foreach ($result as $row) {

            ?>
                <tr>
                    <td>
                        <?php echo $row['itemPicture']; ?>
                    </td>
                    <td>
                        <?php echo $row['itemName']; ?>
                    </td>
                    <td>
                        <?php echo $row['itemState']; ?>
                    </td>
                    <button type="button" class="btn btn-primary meer" data-bs-toggle="modal"
                        data-bs-target="#modal1">Meer...</button>
                    <?php
        }
    } else {
        echo "Niets gevonden";
    }
    ?> <!-- Pop up !MODAL! -->
</tr>

            <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modal1Label">
                                <?php echo $row['itemName'] ?>

                            </h1>
                            <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Modal inhoud -->
                            <div class="form-group">
                                <label>Item</label>
                                <input type="text" name="item" id="item" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Serienummer</label>
                                <input type="text" name="nummer" id="nummer" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Datum uitgeleend</label>
                                <input type="date" name="datumuit" id="datumuit"class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Datum retour</label>
                                <input type="date" name="datumin" id="datumin" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Omschrijving</label>
                                <input type="text" name="omschrijving" id="omschrijving" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Huidige staat</label>
                                <input type="text" name="staat" id="staat" class="form-control" placeholder="">
                            </div>

                            <div class="form-group">
                                <label>Foto</label>
                                <input type="text" name="foto" id="foto" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                            <button type="button" name="save" class="btn btn-primary">Opslaan</button>
                        </div>
                    </div>
                </div>
            </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script>

$(document).ready(function () {
    $('.meer').on('click' , function() {

        $('#modal1').modal('show');

        $tr = $(this).closest('tr');

        var data = $tr.children("td").map(function() {
            return $(this).text();
        }).get();

        console.log(data);
        $('#item').val(data[0]);
        $('#nummer').val(data[1]);
        $('#datumuit').val(data[2]);
        $('#datumin').val(data[3]);
        $('#omschrijving').val(data[4]);
});
});
</script>

</html>

<!-- // echo $row['itemName'];
// echo $row['itemNumber'];
// echo $row['itemDout'];
// echo $row['itemDin'];
// echo $row['itemDescription'];
// echo $row['itemState'];
// echo $row['itemPicture']; -->