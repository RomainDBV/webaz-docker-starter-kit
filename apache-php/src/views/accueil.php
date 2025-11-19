<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Géo'scape Game - Accueil</title>
    <link rel="stylesheet" href="assets/accueil.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue sur Géo'scape Game !</h1>

        <?php if (isset($error)) : ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form action="/carte" method="post">
            <label for="pseudo">Entrez votre pseudo :</label>
            <input type="text" id="pseudo" name="pseudo">
            <button type="submit">Commencer la partie</button>
        </form>
    </div>
</body>
</html>

