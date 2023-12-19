<!-- HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elektronica Uitleen Categorieën</title>
    <link href="cat.css" rel="stylesheet">
</head>

<body>
    <header>
        <h3>Categorieën</h3>
    </header>

    <main>
        <section class="category"> 
            <div class="category-box">
                <h3>Laptops</h3>
                <img src="..." class="category-box-img" alt="Product Photo Laptops">
            </div>
            <div class="category-box">
                <h3>Monitoren</h3>
                <img src="..." class="category-box-img" alt="Product Photo Monitoren">
            </div>
            <div class="category-box">
                <h3>Muizen</h3>
                <img src="..." class="category-box-img" alt="Product Photo Muizen">
            </div>
            <div class="category-box">
                <h3>Toetsenborden</h3>
                <img src="..." class="category-box-img" alt="Product Photo Toetsenborden">
            </div>
            <div class="category-box">
                <h3>Camera's</h3>
                <img src="..." class="category-box-img" alt="Product Photo Cameras">
            </div>
        </section>
    </main>
</body>


<!-- CSS -->
<style>
/* Algemene Document */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Categorie Sectie */
.category {
    width: 100%;
    padding: 60px 0px;
    background-color: #adb5bd;
    display: flex;
    justify-content: center;
    column-gap: 20px;
}

.category-box {
    width: 160px;
    height: 160px;
    background-color: #adb5bd;
}

.category-box h3 {
    font-size: 26px;
    line-height: 32px;
    color: #000;
    font-family: Arial, sans-serif;
    font-weight: 600;
    text-transform: capitalize;
}
</style>