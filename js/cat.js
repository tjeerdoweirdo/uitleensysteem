    function redirectToProduct(productId) {
        window.location.href = '/uitleensysteem/product.php?id=' + productId;
    }

    document.addEventListener("DOMContentLoaded", function() {
        var tableRows = document.querySelectorAll('.cat_table tr');
    tableRows.forEach(function(row, index) {
        setTimeout(function () {
            row.classList.add('fade-in-row');
        }, index * 100);
        });
    });
