function redirectToProduct(productId) {
    window.location.href = '/uitleensysteem/paginas/product.php?id=' + productId;
}

document.addEventListener("DOMContentLoaded", function () {
    var productCards = document.querySelectorAll('.product_card');

    productCards.forEach(function (card, index) {
        setTimeout(function () {
            card.classList.add('fade-in-row');
        }, index * 100);
    });
});
