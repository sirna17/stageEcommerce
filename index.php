<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="UTF-8">
    <!-- Meta description here -->
    <!-- Meta keywords  here -->
    <script src="https://kit.fontawesome.com/02b1d6d912.js" crossorigin="anonymous"></script> <!--Inclusion de la bibliothèque Font Awesome pour les icônes. -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Monda&family=Montserrat&display=swap" rel="stylesheet"> <!--Chargement de deux polices Google (Monda et Montserrat) pour le style de la page. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/media.css">
    <title>Technomania.IO - Product</title>
</head>

<body>
    <!--cette page PHP affiche une liste de produits à partir d'une base de données en utilisant des boucles pour itérer à travers les données et générer le contenu HTML correspondant. Elle utilise également des fichiers inclus (header.php et footer.php) pour le contenu récurrent de l'en-tête et du pied de page.
-->

    <!--Inclusion du fichier header.php qui contient le code HTML pour l'en-tête de la page.-->
    <?php require_once 'inc/header.php'; ?>

    <!--Création d'une section avec un titre "Amazon Hottest Products 🔥".-->

    <section>
        <div class="title">
            <h2>Amazon Hottest Products 🔥</h2>
        </div>
    </section>

    <main>

        <!--Utilisation de PHP pour se connecter à la base de données et récupérer la liste des produits.-->
        <?php
        // Inclure le fichier de configuration de la base de données
        include('config.php');

        // Utilisation de la requête SQL SELECT * FROM produits pour obtenir tous les produits de la base de données.
        $sqlProduits = "SELECT * FROM produits";
        $resultProduits = $connexion->query($sqlProduits);

        // Afficher les produits dans la page
        //Utilisation d'une boucle foreach pour parcourir chaque produit.
       // Pour chaque produit, une requête est faite pour obtenir le chemin de l'image principale associée à ce produit à partir d'une table séparée (images_principales).
        foreach ($resultProduits as $rowProduit) {
            // Récupérer le chemin de l'image principale pour chaque produit
            $sqlImagePrincipale = "SELECT img_principale FROM images_principales WHERE id_produit = :id";
            $stmtImagePrincipale = $connexion->prepare($sqlImagePrincipale);
            $stmtImagePrincipale->bindParam(':id', $rowProduit['id_produit']);
            $stmtImagePrincipale->execute();
            $imagePrincipale = $stmtImagePrincipale->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'image principale existe
            if ($imagePrincipale) {
        ?>
        <!--Si une image principale existe, le produit est affiché sous forme de carte (<div class="card">) avec ses détails.-->
                <div class="card">
                    <div class="box_image">
                        <a href="product-<?= $rowProduit['id_produit'] ?>.html"><img src="back/<?= $imagePrincipale['img_principale'] ?>" alt="<?= $rowProduit['titre'] ?>"></a>
                        <div class="icones">
                            <i class="fa-solid fa-heart"></i>
                            <?= $rowProduit['ratings'] ?>
                            <i class="fa-solid fa-comment-dots"></i>
                            <?= $rowProduit['achat_mensuel'] ?>
                        </div>
                    </div>

                    <div class="infos">
                        <div class="container_title">
                            <h2><?= $rowProduit['titre'] ?></h2>
                        </div>

                        <div class="star">
                            <?php
                            // Utilisation d'une boucle pour afficher des étoiles en fonction du nombre d'étoiles attribuées à chaque produit.
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rowProduit['nb_etoiles']) {
                                    echo '<i class="fa-solid fa-star"></i>';
                                } else {
                                    echo '<i class="fa-regular fa-star"></i>';
                                }
                            }
                            ?>
                            <!--Affichage du prix, du prix réduit (si applicable) et des icônes d'ratings et d'achat mensuel.-->
                            <span><?= $rowProduit['ratings'] ?> ratings</span>
                            <p><?= $rowProduit['achat_mensuel'] ?> bought in past month</p>
                        </div>

                        <h3>$<?= $rowProduit['prix'] ?> <span class="barre">$<?= $rowProduit['prix_reduit'] ?></span> <i class="fa-solid fa-tags"></i></h3>
                    </div>
                </div>
        <?php
            }
        }

        // Fermer la connexion à la base de données
        //Après l'affichage de tous les produits, la connexion à la base de données est fermée ($connexion = null;).
        $connexion = null;
        ?>
    </main>
        <!--Inclusion du fichier footer.php qui contient le code HTML pour le pied de page de la page.-->
    <?php require_once 'inc/footer.php'; ?>
</body>

</html>
