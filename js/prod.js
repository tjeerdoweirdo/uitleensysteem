function redirectToProduct(itemId) {
    window.location.href = '/uitleensysteem/paginas/product.php?id=' + itemId;
}

function openEditModal(itemId) {
    if ($card_product && $card_product[itemId]) {
        $('#update_id').val(itemId);
        $('#modal1').modal('show');
    } else {
        console.error('Error: Unable to retrieve product information for itemId ' + itemId);
    }
}

$(document).ready(function () {
    $('.meer').on('click', function () {
        var $tr = $(this).closest('tr');
        var itemId = $tr.data('item-id');
        console.log('Clicked item ID:', itemId);

        var itemId = $tr.data('item-id');
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

        $('#modal1').modal('show');
    });

    $('.verwijder').on('click', function () {
        var $tr = $(this).closest('tr');
        var itemId = $tr.data('item-itemid');
        $('#delete_id').val(itemId);
        $('#verwijdermodal').modal('show');
    });
});

function redirectToProduct(itemId) {
    window.location.href = '/uitleensysteem/paginas/product.php?id=' + itemId;
}

function openEditModal(itemId) {
    if ($card_product && $card_product[itemId]) {
        $('#update_id').val(itemId);
        $('#modal1').modal('show');
    } else {
        console.error('Error: Unable to retrieve product information for itemId ' + itemId);
    }
}