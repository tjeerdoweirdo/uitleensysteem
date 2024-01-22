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