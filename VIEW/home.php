<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #295be2 0%, #e9e7e7 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fbfbfb;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #333;
            font-size: 2.5em;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #295be2 0%, #e9e7e7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .welcome-text {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 10px;
        }

        .user-name {
            color: #667eea;
            font-size: 1.3em;
            font-weight: bold;
            margin: 20px 0;
        }

        .button-group {
            margin-top: 40px;
            display: flex;
            gap: 15px;
            flex-direction: column;
        }

        a,
        button {
            display: inline-block;
            padding: 15px 30px;
            font-size: 1.1em;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        a.btn-primary {
            background: linear-gradient(135deg, #295be2 0%, #e9e7e7 175%);
            color: white;
        }

        a.btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        a.btn-secondary {
            background: #f0f0f0;
            color: #667eea;
        }

        a.btn-secondary:hover {
            background: #e0e0e0;
            transform: translateY(-3px);
        }

        .icon {
            font-size: 3em;
            margin-bottom: 20px;
        }

        header {
            background: linear-gradient(135deg, #295be2 0%, #295be2 100%);
            color: white;
            padding: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 10;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-badge {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
        }

        body {
            padding-top: 60px;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['user'])): ?>
        <header>
            <div class="header-content">
                <span style="font-weight: 600;">GameHub</span>
                <div class="user-badge">👤 <?php echo htmlspecialchars($_SESSION['user']); ?></div>
            </div>
        </header>
    <?php endif; ?>
    <div class="container">
        <div><img src="../IMAGENES/LogoGameHub.png" alt="Logo GameHub"></div><br><br>


        <?php if (isset($_SESSION['user'])): ?>
            <p class="welcome-text">Bienvenido de vuelta!</p>
            <div class="user-name"><?php echo htmlspecialchars($_SESSION['user']); ?></div>
            <div class="button-group">
                <a href="../VIEW/shop.php" class="btn-primary">Volver a la tienda</a>
            </div>
        <?php else: ?>
            <p class="welcome-text">Tu tienda online para comprar los mejores videojuegos a los mejores precios</p>
            <div class="button-group">
                <a href="login.html" class="btn-primary">Iniciar sesión</a>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>