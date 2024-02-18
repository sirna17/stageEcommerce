<!DOCTYPE html> <!--Déclaration de la version HTML utilisée (HTML5).-->
<html lang="en-us"> <!-- Balise HTML principale avec la spécification de la langue (anglais américain).-->

<head>
    <meta charset="UTF-8">
    <!-- Meta description here -->
    <!-- Meta keywords  here -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/b408c921ca.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Monda&family=Montserrat&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/product.css">
    <title>Technomania.IO - Product</title>
</head>

<body>
    <?php require_once 'inc/header.php'; ?>

    <!-- Début des propriétés CSS du container principal -->
    <main>
        <section>
            <?php
            // Inclure le fichier de configuration de la base de données
            include('config.php');

            // Vérifier si l'id du produit est valide
            if (!isset($_GET['id_product']) || $_GET['id_product'] < 1) {
                header('location: index.php');
                exit;
            }

            $id_product = $_GET['id_product'];

            // Récupérer les informations du produit depuis la base de données
            $sqlProduit = "SELECT * FROM produits WHERE id_produit = :id";
            $stmtProduit = $connexion->prepare($sqlProduit);
            $stmtProduit->bindParam(':id', $id_product);
            $stmtProduit->execute();
            $product = $stmtProduit->fetch(PDO::FETCH_ASSOC);

            // Vérifier si le produit existe
            if (!$product) {
                header('location: index.php');
                exit;
            }

            // Récupérer le chemin de l'image principale
            $sqlImagePrincipale = "SELECT img_principale FROM images_principales WHERE id_produit = :id";
            $stmtImagePrincipale = $connexion->prepare($sqlImagePrincipale);
            $stmtImagePrincipale->bindParam(':id', $id_product);
            $stmtImagePrincipale->execute();
            $imagePrincipale = $stmtImagePrincipale->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'image principale existe
            if (!$imagePrincipale) {
                // Rediriger vers la page d'accueil  après l'insertion
                header('location: index.php');
                exit;
            }
            

               // Récupérer les avantages du produit depuis la base de données
               $sqlAvantage = "SELECT avantage FROM avantages WHERE id_produit = :id";
               $stmtAvantage = $connexion->prepare($sqlAvantage);
               $stmtAvantage->bindParam(':id', $id_product);
               $stmtAvantage->execute();
               $Avantage = $stmtAvantage->fetch(PDO::FETCH_ASSOC);
   
               // Vérifier si le produit existe
               if (!$Avantage) {
                   header('location: index.php');
                   exit;
               }

                // Récupérer les commentaires du produit depuis la base de données
                $sqlCommentaires = "SELECT * FROM commentaires WHERE id_produit = :id";
                $stmtCommentaires = $connexion->prepare($sqlCommentaires);
                $stmtCommentaires->bindParam(':id', $id_product);
                $stmtCommentaires->execute();
                $Commentaires = $stmtCommentaires->fetch(PDO::FETCH_ASSOC);

                // Vérifier si le produit existe
                if (!$Commentaires) {
                    header('location: index.php');
                    exit;
                }

   
            ?>

            <!-- Galerie d'images -->
            <div id="galerie">
                <div id="big">
                    <a class="link" href="index.php"> &lt; Back to home</a>
                    <img src="back/<?= $imagePrincipale['img_principale']; ?>" alt="<?= $product['titre']; ?>">
                </div>
                <div id="container-thumbs">
                    <?php
                    // Afficher les miniatures
                    $sqlThumbs = "SELECT * FROM images_miniatures WHERE id_produit = :id";
                    $stmtThumbs = $connexion->prepare($sqlThumbs);
                    $stmtThumbs->bindParam(':id', $id_product);
                    $stmtThumbs->execute();
                    $thumbs = $stmtThumbs->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($thumbs as $thumb) : ?>
                        <div class="thumb">
                            <img src="back/<?= $thumb['img_thumbs']; ?>" alt="">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Informations sur le produit -->
            <div class="details">
                <h1><?= $product['titre']; ?></h1>
                <div class="star">
                    <!-- Utilisez une boucle pour afficher les étoiles -->
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <?php if ($i <= $product['nb_etoiles']) : ?>
                            <i class="fa-solid fa-star"></i>
                        <?php else : ?>
                            <i class="fa-regular fa-star"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <span><?= $product['ratings']; ?> ratings</span>
                </div>

                <?php
                // Afficher la balise h2 uniquement si $product['achat_mensuel'] n'est pas vide
                if (!empty($product['achat_mensuel'])) {
                    echo "<h2>{$product['achat_mensuel']}+ bought in past month</h2>";
                }
                ?>

                <div><?= $product['avantages']; ?></div>
                <h3>$<?= $product['prix']; ?><span class="barre">$<?= $product['prix_reduit']; ?></span><i class="fa-solid fa-tags"></i></h3>
                <div class="button">
                    <a href="<?= $product['lien_amazon']; ?>" target="_blank"><i class="fa-brands fa-amazon"></i> Buy on Amazon</a>
                </div>
            </div>
        </section>

        <div class="separator"></div>
        <section>
        <div class="advantage">
        <h3>About this item :</h3>
        <div><?=$Avantage['avantage']?></div>
</div>
</section>
<section>
    <div class="advantage">
        <h3>Customer reviews:</h3>
        <div><?=$Commentaires['commentaire']?></div>
    </div>
    <div class="remark">
        <h2>🌟 Real reviews from Amazon 🌟</h2>
    </div>

    </section>

        <?php
        // Afficher les détails supplémentaires du produit
        if (!empty($product['fiche_product'])) {
            echo file_get_contents($product['fiche_product']);
        }
        ?>
    </main>
    <!-- Fin des propriétés html du container principal -->

    <?php require_once 'inc/footer.php'; ?>

    <script>
        // Gestion affichage des images miniatures en grand
        let imageBig = document.querySelector('#big img');
        let imageThumb = document.querySelectorAll('.thumb img');

        // Affiche l'image en grand au survol de la miniature
        imageThumb.forEach(thumb => {
            thumb.addEventListener('mouseover', () => {
                imageBig.src = thumb.src;
            });
        });
    </script>
</body>

</html>
