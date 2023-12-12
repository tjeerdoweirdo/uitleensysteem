<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>elektronica lenen App</title>
</head>
<style> /* styles.css */

body {
    display: flex;
    flex-direction: column;
    min-height: 100vh;
    margin: 0;
}

main {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.search-bar {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}

#search {
    width: 300px;
    padding: 10px;
    font-size: 16px;
    margin-bottom: 10px;
}

.btn {
    padding: 10px;
    font-size: 16px;
}

.features {
    display: flex;
    justify-content: space-around;
    margin-top: 20px;
}
 </style>
<body>

    <header>
        <h1>Item Lending App</h1>
    </header>

    <main>
        <section class="hero">
            <h2>Deel leen overzicht</h2>
            <p>de overzicht voor alle elektronica</p>
        </section>

        <section class="search-bar">
            <input type="text" id="search" name="search" placeholder="Zoeken...">
            <button class="btn">Zoeken</button>
        </section>

        <section class="features">
            <div class="feature">
                <h3>Easy uitlenen</h3>
                <a href="itemboard.php" class="btn">Browse</a>
            </div>
            <div class="feature">
            <h3>Over deze applicatie</h3>
                <p>Welkom op de pagina van onze gloednieuwe applicatie voor het beheer van school elektronica-uitlenen! Deze gebruiksvriendelijke app is speciaal ontworpen om jouw ervaring met het lenen van elektronische apparatuur op school te optimaliseren. Of je nu op zoek bent naar een laptop, tablet of ander elektronisch apparaat, deze app biedt een gestroomlijnde manier om het beschikbare assortiment te verkennen.</p>
            </div> 
            <div class="feature">
                <h3>Bevoegden inlog</h3>
                <a href="login.php" class="btn">inloggen</a>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; Firda</p>
    </footer>

</body>

</html>
