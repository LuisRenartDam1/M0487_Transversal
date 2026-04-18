<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

// Product catalog (price + initial stock)
$products = [
    "EA FC26"   => ["price" => 79.99, "stock" => 78,  "img" => "../IMAGENES/FC26.jpg"],
    "Spider-Man 2"    => ["price" => 59.99,  "stock" => 45, "img" => "../IMAGENES/SpiderMan2.jpg"],
    "NBA 2K2026" => ["price" => 69.99,  "stock" => 87,  "img" => "../IMAGENES/2K26.jpg"],
    "India Jones"  => ["price" => 39.99, "stock" => 12,  "img" => "../IMAGENES/IndianaJones.jpg"],
    "Resident Evil Requiem"   => ["price" => 79.99, "stock" => 78,  "img" => "../IMAGENES/ResidentEvilRequiem.jpg"],
    "Ark Survival Ascended"   => ["price" => 79.99, "stock" => 78,  "img" => "../IMAGENES/ArkSurvivlaAscended.jpg"],
    "Terraria"   => ["price" => 79.99, "stock" => 78,  "img" => "../IMAGENES/Terraria.jpg"],
    "Stardew Valley"   => ["price" => 79.99, "stock" => 78,  "img" => "../IMAGENES/StardewValley.jpg"],
];

// Initialize stock in session (only once)
if (!isset($_SESSION['stock'])) {
    foreach ($products as $name => $data) {
        $_SESSION['stock'][$name] = $data['stock'];
    }
}

// Initialize cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_POST['product'])) {
    $product = $_POST['product'];

    if (
        isset($_SESSION['stock'][$product]) &&
        $_SESSION['stock'][$product] > 0 
    ) {
        // Increase quantity in cart
        if (!isset($_SESSION['cart'][$product])) {
            $_SESSION['cart'][$product] = 1;
        } else {
            $_SESSION['cart'][$product]++;
        }

        // Decrease stock
        $_SESSION['stock'][$product]--;
    }
}

// Calculate total price
$totalPrice = 0;
foreach ($_SESSION['cart'] as $item => $qty) {
    $totalPrice += $products[$item]['price'] * $qty; 
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        header {
            background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        h1 {
            font-size: 2em;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.2);
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cart-info {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .user-info {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            color: #999;
            font-size: 0.9em;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1.2em;
            font-weight: bold;
            color: #667eea;
        }

        .cart-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            font-size: 0.95em;
        }

        .btn-checkout {
            background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
            color: white;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-logout {
            background: #f0f0f0;
            color: #333;
        }

        .btn-logout:hover {
            background: #e0e0e0;
        }

        .products-section h2 {
            font-size: 1.8em;
            margin-bottom: 30px;
            color: #333;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .product-icon {
    background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
    height: 200px; 
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
}

.product-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover; 
    transition: transform 0.3s ease;
}


.product-card:hover .product-icon img {
    transform: scale(1.1);
}

        .product-info {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .product-price {
            font-size: 1.5em;
            color: #667eea;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-card form {
            display: flex;
        }

        .product-card button {
            background: linear-gradient(135deg, #295be2 0%, #0579ec 100%);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .product-card button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        footer {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #ccc;
            padding: 50px 20px 25px;
            margin-top: auto;
        }
 
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 40px;
            flex-wrap: wrap;
        }
 
        /* Brand block */
        .footer-brand .footer-logo {
            font-size: 1.6em;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
 
        .footer-brand p {
            font-size: 0.85em;
            color: #888;
            max-width: 200px;
            line-height: 1.5;
        }
 
        /* Menu block */
        .footer-menu h4 {
            letter-spacing: 2px;
            font-size: 0.78em;
            color: white;
            margin-bottom: 18px;
        }
 
        .footer-menu ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
 
        .footer-menu ul li a {
            color: #aaa;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
        }
 
        /* Social block */
        .footer-social h4 {
            letter-spacing: 2px;
            font-size: 0.78em;
            color: white;
            margin-bottom: 18px;
        }
 
        .social-icons {
            display: flex;
            gap: 14px;
        }
 
        .social-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #ccc;
            font-size: 1.1em;
            background: rgba(255,255,255,0.04);
        }
 
        /* SVG icons inside anchors */
        .social-icon svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }
 
        /* Divider & copyright */
        .footer-bottom {
            max-width: 1200px;
            margin: 35px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.08);
            text-align: center;
            font-size: 0.8em;
            color: #555;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 15px;
            }

            .cart-info {
                flex-direction: column;
                text-align: center;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
            }

            .footer-content {
                flex-direction: column; gap: 30px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <h1>GameHub</h1>
            <div style="display: flex; gap: 15px; align-items: center;">
                <span style="background: rgba(255, 255, 255, 0.2); padding: 8px 16px; border-radius: 20px; font-weight: 600; font-size: 0.9em;">👤 <?php echo htmlspecialchars($_SESSION['user']); ?></span>
                <div class="nav-links">
                    <a href="../VIEW/home.php">Página Principal</a>
                    <a href="../VIEW/">Cerrar Session</a>
                    <a href="../VIEW/aboutus.html">Sobre nosotros</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="cart-info">
        <div class="user-info">
    <div class="info-item">
        <span class="info-label">Productos en la Cesta</span>
        <span class="info-value">
            <?= array_sum($_SESSION['cart']); ?>
        </span>
    </div>

    <div class="info-item">
        <span class="info-label">Detalles de la Cesta</span>
        <span class="info-value" style="font-size:0.95em; font-weight:500; color:#333;">
            <?php if (empty($_SESSION['cart'])): ?>
                Vacio
            <?php else: ?>
                <?php foreach ($_SESSION['cart'] as $item => $qty): ?>
                    <?= htmlspecialchars($item) ?>
                    (<?= $qty ?> × €<?= number_format($products[$item]['price'], 2) ?>)
                    = €<?= number_format($products[$item]['price'] * $qty, 2) ?><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </span>
    </div>

    <div class="info-item">
        <span class="info-label">Precio Total</span>
        <span class="info-value">
            €<?= number_format($totalPrice, 2); ?>
        </span>
    </div>
</div>

            
            <div class="cart-actions">
                <a href="checkout.php" class="btn btn-checkout">Pagar el Pedido</a>
            </div>
        </div>

        <div class="products-section">
            <h2>Featured Products</h2>
            <div class="products-grid">
                <?php
                

                foreach ($products as $name => $data):
                    $price = $data['price'];
                    $stock = $_SESSION['stock'][$name];
                ?>
                    <div class="product-card">
                        <div class="product-icon">
                            <img src="<?= $data['img']; ?>" alt="<?= htmlspecialchars($name); ?>">
                        </div>

                        <div class="product-info">
                            <div class="product-name">
                                <?= htmlspecialchars($name); ?>
                            </div>

                            <div class="product-price">
                                €<?= number_format($price, 2); ?>
                            </div>

                            <div style="margin-bottom:12px; font-weight:600;
                        color: <?= $stock > 0 ? '#28a745' : '#dc3545' ?>">
                                Stock: <?= $stock ?>
                            </div>

                            <form method="post">
                                <input type="hidden" name="product" value="<?= htmlspecialchars($name); ?>">

                                <button type="submit" <?= $stock === 0 ? 'disabled' : '' ?>>
                                    <?= $stock === 0 ? 'Out of stock' : 'Add to Cart' ?>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <footer>
        <div class="footer-content">
 
            <div class="footer-brand">
                <div class="footer-logo">GameHub</div>
                <p>Tu tienda de videojuegos de confianza.</p>
            </div>
 
            <div class="footer-menu">
                <h4>MENÚ</h4>
                <ul>
                    <li><a href="../VIEW/home.php">Página principal</a></li>
                    <li><a href="../VIEW/aboutus.html">Sobre nosotros</a></li>
                    <li><a href="checkout.php">Ver el Pedido</a></li>
                </ul>
            </div>
 
            <div class="footer-social">
                <h4>SÍGUENOS</h4>
                <div class="social-icons">
 
                    <a href="LINK_FACEBOOK" class="social-icon" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                        </svg>
                    </a>
 
                    <a href="LINK_X_TWITTER" class="social-icon" target="_blank" rel="noopener" aria-label="X">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
 
                    <a href="LINK_INSTAGRAM" class="social-icon" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" fill="none" stroke="currentColor" stroke-width="2"/>
                            <circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/>
                            <circle cx="17.5" cy="6.5" r="1.2"/>
                        </svg>
                    </a>
 
                </div>
            </div>
 
        </div>
 
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> GameHub. Todos los derechos reservados.
        </div>
    </footer>

</body>

</html>