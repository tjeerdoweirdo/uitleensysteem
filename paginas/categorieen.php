<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Categorieën</title>
</head>

<body>
    <header>
        <h1>Categorieën</h1>
    </header>

    <main>
        <section class="category"> <!-- sectie voor alle kaarten -->
                <!-- Categorie: Laptops -->
                <a href="#" class="category-box"> <!-- categorie kaart + link naar item overzicht -->
                    <h3>Laptops</h3> <!-- categorie naam -->
                    <img src="..." class="category-box-img" alt="Product Photo Laptops"> <!-- categorie foto -->
                <!-- Categorie: Monitoren -->
                <a href="#" class="category-box">
                    <h3>Monitoren</h3>
                    <img src="..." class="category-box-img" alt="Product Photo Monitoren">
                </a>
                <!--Categorie: Muizen -->
                <a href="#" class="category-box">
                    <h3>Muizen</h3>
                    <img src="..." class="category-box-img" alt="Product Photo Muizen">
                </a>
                <!-- Categorie: Toetsenborden -->
                <a href="#" class="category-box">
                    <h3>Toetsenborden</h3>
                    <img src="..." class="category-box-img" alt="Product Photo Toetsenborden">
                </a>
                <!-- Categorie: Camera's -->
                <a href="#" class="category-box">
                    <h3>Camera's</h3>
                    <img src="..." class="category-box-img" alt="Product Photo Cameras">
                </a>
        </section>
    </main>
</body>


<!-- CSS -->
<style>
/* tags */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

a {
    text-decoration: none; /* haalt hyperlink underline weg */
}

/* categorie sectie */
.category {
    width: 100%;
    padding: 60px 0px;
    display: flex;
    justify-content: center; /* centreren van alle categorie kaarten*/
    column-gap: 20px; /* padding tussen de kaarten */
}

.category-box {
    width: 230px;
    height: 230px;
    background-color: #e5e5e5;
}

.category-box h3 {
    font-size: 20px;
    line-height: 32px;
    color: #000;
    font-family: Arial, sans-serif;
    font-weight: 600;
    text-transform: capitalize;
    text-align: center;
}
</style>