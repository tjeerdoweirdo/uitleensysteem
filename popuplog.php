<?php
require_once 'includes/db_connection.php';

$sql = "SELECT * FROM items;";
$result = mysqli_query($conn, $sql);


?>

<!DOCTYPE html>
<html lang="en">
<style>
img {
    height: 100px;
    width: 100px;
}

</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Importeer Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </link>
    <title>Document</title>
</head>

<body>
    <table>
        <tbody>
            <?php if ($result) {

                foreach ($result as $row) {

                    ?>
                    <tr data-item-itemid="<?php echo $row['itemId']; ?>" data-item-name="<?php echo $row['itemName']; ?>"
                        data-item-description="<?php echo $row['itemDescription']; ?>"
                        data-item-number="<?php echo $row['itemNumber']; ?>" data-item-dout="<?php echo $row['itemDout']; ?>"
                        data-item-din="<?php echo $row['itemDin']; ?>"
                        data-item-picture="<?php echo base64_encode($row['itemPicture']); ?>"
                        data-item-state="<?php echo $row['itemState']; ?>">
                        
                        <td><img src="data:image/jpeg;base64,<?php echo base64_encode($row['itemPicture']); ?>"
                                alt="<?php echo $row['itemName']; ?>"></td>
                        <td>
                            <?php echo $row['itemName']; ?>
                        </td>
                        <td>
                            <?php echo $row['itemState']; ?>
                        </td>
                        <td><button type="button" class="btn btn-primary meer" data-bs-toggle="modal"
                                data-bs-target="#modal1">Meer...</button> </td>
                    </tr>
                    <?php
                }
            } else {
                echo "Niets gevonden";
            }
            ?>
        </tbody>
    </table>

    <!-- Pop up !MODAL! -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <form id="save" method="POST" action="includes/save.php" enctype="multipart/form-data">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modal1Label">
                            <?php echo $row['itemName'] ?>

                        </h1>
                        <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="container">

                            <!-- Modal inhoud -->
                            <input type="hidden" name="update_id" id="update_id">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Item:</label>
                                        <input type="text" name="item" id="item" class="form-control" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label>Serienummer:</label>
                                        <input type="text" name="nummer" id="nummer" class="form-control"
                                            placeholder="">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label>Categorie:</label>
                                        <input type="text" name="cat" id="cat" class="form-control"
                                            placeholder="">
                                    </div>

                                    <div class="d-flex mb-3" style="margin-bottom: 0px!important;">
                                        <div class="form-group p-2" style="padding-left: 0px!important;">
                                            <label>Datum uitgeleend:</label>
                                            <input type="date" name="datumuit" id="datumuit" class="form-control"
                                                placeholder="">
                                        </div>

                                        <div class="form-group p-2">
                                            <label>Datum retour:</label>
                                            <input type="date" name="datumin" id="datumin" class="form-control"
                                                placeholder="">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Huidige staat:</label><br>
                                        <select class="form-select" id="staat" name="staat">
                                        <option value="Beschikbaar">Beschikbaar</option>
                                        <option value="Uitgeleend">Uitgeleend</option>
                                        <option value="Reparatie">Reparatie</option>
                                        <option value="Kapot">Kapot</option>
                                        <option value="Anders">Anders (zie omschrijving)</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Omschrijving:</label>
                                        <input type="text" name="omschrijving" id="omschrijving" class="form-control"
                                            placeholder="">
                                    </div>
                                </div>
                            
                            <div class="col">
                                <div class="form-group">
                                    <label>Foto:</label>
                                    <img style="height: 500px; width: 550px;" src="data:image/jpeg;base64,<?php echo base64_encode($row['itemPicture']); ?>"
                                        alt="<?php echo $row['itemName']; ?>">
                                    <input type="file" name="foto" id="foto" class="form-control" style="margin-top: 8px;">
                                </div>
                            </div>
                        </div>
                    </div>
</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger verwijder" data-bs-target="#verwijdermodal" data-bs-toggle="verwijdermodal">Verwijderen</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        <button type="submit" name="savedata" class="btn btn-primary">Opslaan</button>
                    </div>
                </div>
            </div>
    </div>

    </form>


                                        <!-- Verwijder Modal -->

    <div class="modal fade" id="verwijdermodal" tabindex="-1" aria-labelledby="verwijdermodalLabel" aria-hidden="true">
   
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5">Item verwijderen</h4>
                    <button type=button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div> 
                <form method="POST" action="includes/deleteitem.php">
                    <div class="modal-body">
                    <input type="hidden" name="delete_id" id="delete_id">
                        <p>Weet u zeker dat u dit item wilt verwijderen?</p>
                </div>
                <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                      <button type="submit" name="deleteitem" class="btn btn-danger">Verwijderen</button>
                </div>
            </div>
        </form>
        </div>
    </div>
        
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>

<script>
$(document).ready(function () {
    $('.verwijder').on('click', function () {
        $('#verwijdermodal').modal('show');

        var $tr = $(this).closest('tr');
        var itemId = $tr.data('item-itemid');
        $('#delete_id').val(itemId);
    });
});

</script>

<script>


    $(document).ready(function () {
        $('.meer').on('click', function () {
            $('#modal1').modal('show');

            var $tr = $(this).closest('tr');

            var itemId = $tr.data('item-itemid');
            var itemName = $tr.data('item-name');
            var itemDescription = $tr.data('item-description');
            var itemNumber = $tr.data('item-number');
            var itemDout = $tr.data('item-dout');
            var itemDin = $tr.data('item-din');
            var itemPicture = $tr.data('item-picture');
            var itemState = $tr.data('item-state');

            $('#modal1Label').text(itemName);

            $('#update_id').val(itemId);
            $('#item').val(itemName);
            $('#omschrijving').val(itemDescription);
            $('#nummer').val(itemNumber);
            $('#datumuit').val(itemDout);
            $('#datumin').val(itemDin);
            $('#staat').val(itemState);
            $('#foto').val(itemPicture);


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